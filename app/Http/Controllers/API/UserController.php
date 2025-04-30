<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

use Illuminate\Support\Facades\Auth;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

use Barryvdh\DomPDF\Facade\Pdf;


class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('permission:voir utilisateurs')->only(['index', 'show','roles']);
        $this->middleware('permission:attribuer roles')->only(['assignRole']);
        $this->middleware('permission:supprimer utilisateurs')->only('destroy');
        

    }

    // ✅ Lister tous les utilisateurs avec leurs rôles, fonction de recherche, pagination aussi
    public function index(Request $request)
    {
        $query = User::with('roles');
    
        // 🔍 Recherche
        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
    
        // 🔃 Tri
        if ($sortBy = $request->query('sortBy')) {
            $sortDir = $request->query('sortDir', 'asc');
            $query->orderBy($sortBy, $sortDir);
        }
    
        // 📄 Pagination
        $perPage = $request->query('perPage', 10);
        $users = $query->paginate($perPage);
    
        return response()->json($users);
    }
    

    // ✅ Afficher un utilisateur spécifique
    public function show($id)
    {
        $user = User::with('roles')->findOrFail($id);
        return response()->json($user);
    }

    // ✅ Mettre à jour un utilisateur
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name'  => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user->update($validator->validated());

        return response()->json([
            'message' => 'Utilisateur mis à jour avec succès.',
            'user'    => $user
        ]);
    }

    // ✅ Attribuer un rôle à un utilisateur
    public function assignRole(Request $request, $id)
    {
        $request->validate([
            'role' => 'required|string|exists:roles,name',
        ]);

        $user = User::findOrFail($id);

        $user->syncRoles([$request->role]);

        return response()->json([
            'message' => 'Rôle attribué avec succès.',
            'user'    => $user,
            'roles'   => $user->getRoleNames()
        ]);
    }

    // ✅ Supprimer un utilisateur
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json([
            'message' => 'Utilisateur supprimé avec succès.',
        ]);
    }

    // ✅ Lister tous les rôles disponibles
    public function roles()
    {
        $roles = Role::all(['id', 'name']); // tu peux ajouter description si tu l’as
        return response()->json($roles);
    }


    // ✅ Créer un nouvel utilisateur avec rôle (si fourni)
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role'     => 'nullable|string|exists:roles,name',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
        ]);

        if ($request->filled('role')) {
            $user->assignRole($request->role);
        }

        return response()->json([
            'message' => 'Utilisateur créé avec succès.',
            'user'    => $user->load('roles'), // pour retourner le(s) rôle(s) avec l'utilisateur
        ], 201);
    }


    //fonction pour exporter la liste des utilsateurs en fichier excel
    public function exportExcel()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // En-têtes
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Nom');
        $sheet->setCellValue('C1', 'Email');
        $sheet->setCellValue('D1', 'Date de création');

        // Données
        $users = User::all();
        $row = 2;

        foreach ($users as $user) {
            $sheet->setCellValue('A' . $row, $user->id);
            $sheet->setCellValue('B' . $row, $user->name);
            $sheet->setCellValue('C' . $row, $user->email);
            $sheet->setCellValue('D' . $row, $user->created_at->format('d/m/Y H:i'));
            $row++;
        }

        // Retourner le fichier en téléchargement
        $writer = new Xlsx($spreadsheet);

        // Générer un nom de fichier
        $fileName = 'utilisateurs_' . now()->format('Ymd_His') . '.xlsx';

        // Créer une réponse HTTP avec le fichier
        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }



    public function exportPdf()
    {
        $users = User::all();

        $html = '<h2>Liste des utilisateurs</h2><table border="1" width="100%" cellspacing="0" cellpadding="5"><tr><th>Nom</th><th>Email</th><th>Rôles</th></tr>';
        
        foreach ($users as $user) {
            $roles = implode(', ', $user->getRoleNames()->toArray());
            $html .= "<tr><td>{$user->name}</td><td>{$user->email}</td><td>{$roles}</td></tr>";
        }

        $html .= '</table>';

        $pdf = Pdf::loadHTML($html);
        return $pdf->download('utilisateurs.pdf');
    }


    public function me(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'user' => $user,
            'roles' => $user->getRoleNames(),
            'code_patient' => optional($user->patient)->code_patient,
            'image_profile' => $user->image_profile ? asset('storage/' . $user->image_profile) : null,
        ]);
    }


    


    
}

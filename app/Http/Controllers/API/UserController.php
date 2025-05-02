<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\MediaCollections\Models\Media;



use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;

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
                $q->where('nom', 'like', "%{$search}%")
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

    // ✅ Mettre à jour profil d'un utilisateur

    public function update(Request $request)
    {
        // 1. Validation
        $validatedData = $request->validate([
            'nom' => 'sometimes|string|max:255',
            'prenom' => 'sometimes|string|max:255',
            'telephone' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $request->user()->id,
            'profile_illustratif' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // 2. Récupération de l'utilisateur
        $user = $request->user();
        $user->fill($validatedData);

        // 3. Gestion de l'image
        if ($request->hasFile('profile_illustratif')) {
            // Supprime l'ancienne image si elle existe
            $user->clearMediaCollection('profile_images');
            
            // Ajoute la nouvelle image avec conversion
            $user->addMediaFromRequest('profile_illustratif')
                ->withResponsiveImages() // Génère des versions responsive
                ->usingFileName(md5($request->file('profile_illustratif')->getClientOriginalName()) . '.' . $request->file('image_profile')->extension())
                ->toMediaCollection('profile_images');
        }

        $user->save();

        // 4. Récupération de l'URL optimisée
        $media = $user->getFirstMedia('profile_images');
        
        return response()->json([
            'success' => true,
            'message' => 'Profil mis à jour avec succès.',
            'data' => [
                'user' => $user->only(['id', 'nom', 'email']),
                'profile_illustratif' => $media ? [
                    'original' => $media->getUrl(),
                    'thumbnail' => $media->getUrl('thumb'), // Conversion 'thumb'
                    'responsive' => $media->getResponsiveImageUrls() // Toutes les tailles
                ] : null
            ]
        ]);
    }

    //créer utilisateur sans lui attribuer rôle

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom'     => 'required|string|max:255',
            'prenom'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role'     => 'nullable|string|exists:roles,name',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'nom'     => $request->nom,
            'prenom'     => $request->prenom,
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

    // ✅ Désactiver un utilisateur
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json([
            'message' => 'Utilisateur désactivé avec succès.',
        ]);
    }


    // ✅ Lister tous les rôles disponibles
    public function roles()
    {
        $roles = Role::all(['id', 'name']); // tu peux ajouter description si tu l’as
        return response()->json($roles);
    }


    // ✅ Créer un nouvel utilisateur et lui attribuer un rôle, gérer par l'admin 

    public function storeUserWithRole(Request $request):JsonResponse
    {
        // Validation
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'role_name' => 'required|string|exists:roles,name' // Validation par nom de rôle
        ]);

        // Création de l'utilisateur
        $user = User::create([
            'nom' => $validated['nom'],
            'prenom' => $validated['prenom'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);

        // Attribution directe par nom de rôle
        $user->assignRole($validated['role_name']);

        return response()->json([
            'message' => 'Utilisateur créé avec succès',
            'user' => $user->only(['id', 'nom', 'email']),
            'role' => $validated['role_name']
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
            'patient_code' => optional($user->patient)->code_patient,
            'profile_illustratif' => $user->profile_illustratif ? asset('storage/' . $user->profile_illustratif) : null,
        ]);
    }


    


    
}

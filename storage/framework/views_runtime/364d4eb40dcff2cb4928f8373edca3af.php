<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo $__env->yieldContent('title', 'Gestion des Absences'); ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root{
            --bg:#f6f8fc;
            --card:#ffffff;
            --text:#0f172a;
            --muted:#64748b;
            --border:#e5e7eb;
            --primary:#2563eb;
            --radius:14px;
        }

        body{
            background:var(--bg);
            font-family: system-ui, Arial;
            color:var(--text);
        }

        /* ===== SIDEBAR ===== */
        .sidebar{
            position:fixed;
            inset:0 auto 0 0;
            width:250px;
            background:var(--card);
            border-right:1px solid var(--border);
            padding:22px;
        }

        .brand{
            font-size:18px;
            font-weight:800;
            margin-bottom:24px;
        }

        .nav-link{
            display:flex;
            padding:10px 12px;
            border-radius:10px;
            color:var(--muted);
            font-size:14px;
            margin-bottom:6px;
            transition:.2s;
        }

        .nav-link:hover{
            background:#f1f5f9;
            color:var(--text);
        }

        .nav-link.active{
            background:var(--primary);
            color:#fff;
        }

        /* ===== MAIN ===== */
        .main{
            margin-left:250px;
            padding:24px;
        }

        /* ===== TOPBAR ===== */
        .topbar{
            background:var(--card);
            border:1px solid var(--border);
            padding:14px 18px;
            border-radius:var(--radius);
            display:flex;
            justify-content:space-between;
            align-items:center;
            margin-bottom:20px;
        }

        .page-title{
            font-size:18px;
            font-weight:800;
        }

        /* ===== CARDS ===== */
        .card-box{
            background:var(--card);
            border:1px solid var(--border);
            border-radius:var(--radius);
            padding:18px;
        }

        .metric-label{
            font-size:12px;
            color:var(--muted);
            text-transform:uppercase;
            font-weight:600;
        }

        .metric-value{
            font-size:28px;
            font-weight:800;
            margin-top:6px;
        }

        /* ===== TABLE ===== */
        table{
            width:100%;
            background:var(--card);
            border:1px solid var(--border);
            border-radius:var(--radius);
            overflow:hidden;
        }

        th{
            font-size:12px;
            color:var(--muted);
            text-transform:uppercase;
            background:#f8fafc;
        }

        td,th{
            padding:12px 14px;
        }

        tbody tr:hover{
            background:#f9fafb;
        }

        /* ===== BUTTON ===== */
        .btn{
            border-radius:10px;
        }

        /* ===== MODERN SHELL OVERRIDES ===== */
        body{
            background:
                radial-gradient(circle at top left, rgba(37,99,235,0.08), transparent 30%),
                linear-gradient(180deg, #f8fafc 0%, #eef3f8 100%);
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Arial, sans-serif;
        }

        .sidebar{
            width:272px;
            padding:24px 18px;
            background:rgba(255,255,255,0.92);
            backdrop-filter:blur(14px);
            box-shadow:12px 0 30px rgba(15,23,42,0.04);
        }

        .brand{
            display:flex;
            align-items:center;
            gap:12px;
            font-size:17px;
            letter-spacing:0;
            margin-bottom:28px;
            padding:6px 4px;
        }

        .brand-mark,
        .topbar-mark{
            display:grid;
            place-items:center;
            width:38px;
            height:38px;
            border-radius:12px;
            background:linear-gradient(135deg, #2563eb, #14b8a6);
            color:#fff;
            font-weight:900;
            box-shadow:0 12px 24px rgba(37,99,235,0.22);
            flex:0 0 auto;
        }

        .nav-link{
            align-items:center;
            gap:10px;
            padding:12px 14px;
            border-radius:12px;
            font-weight:650;
            letter-spacing:0;
        }

        .nav-link.active{
            background:#0f172a;
            box-shadow:0 12px 24px rgba(15,23,42,0.14);
        }

        .main{
            margin-left:272px;
            padding:28px;
        }

        .topbar{
            min-height:76px;
            padding:16px 18px;
            border:1px solid rgba(226,232,240,0.9);
            border-radius:20px;
            box-shadow:0 18px 45px rgba(15,23,42,0.06);
        }

        .topbar-brand{
            display:flex;
            align-items:center;
            gap:14px;
        }

        .topbar-mark{
            width:42px;
            height:42px;
        }

        .page-title{
            font-size:22px;
            line-height:1.15;
        }

        .app-eyebrow{
            color:#64748b;
            font-size:12px;
            font-weight:800;
            letter-spacing:0.08em;
            text-transform:uppercase;
        }

        .card-box{
            border:1px solid rgba(226,232,240,0.95);
            border-radius:18px;
            box-shadow:0 16px 38px rgba(15,23,42,0.06);
        }

        .table{
            border-collapse:separate;
            border-spacing:0 8px;
            border:0;
            background:transparent;
        }

        .table thead th{
            border:0;
            background:transparent;
            color:#64748b;
            font-size:11px;
            font-weight:800;
            letter-spacing:0.08em;
        }

        .table tbody tr{
            background:#fff;
            box-shadow:0 8px 20px rgba(15,23,42,0.04);
            transition:transform .18s ease, box-shadow .18s ease, background .18s ease;
        }

        .table tbody tr:hover{
            background:#f8fafc;
            transform:translateY(-1px);
            box-shadow:0 14px 28px rgba(15,23,42,0.07);
        }

        .table tbody td{
            border-top:1px solid #eef2f7;
            border-bottom:1px solid #eef2f7;
            padding:16px 14px;
            vertical-align:middle;
        }

        .table tbody td:first-child{
            border-left:1px solid #eef2f7;
            border-radius:14px 0 0 14px;
        }

        .table tbody td:last-child{
            border-right:1px solid #eef2f7;
            border-radius:0 14px 14px 0;
        }

        .status-badge{
            display:inline-flex;
            align-items:center;
            justify-content:center;
            min-width:86px;
            min-height:30px;
            padding:6px 12px;
            border-radius:999px;
            font-size:12px;
            font-weight:800;
            line-height:1;
            letter-spacing:0;
            white-space:nowrap;
        }

        .status-badge--present{ background:#dcfce7; color:#15803d; }
        .status-badge--absent{ background:#fee2e2; color:#b91c1c; }
        .status-badge--repos{ background:#ede9fe; color:#5b21b6; }
        .status-badge--conge{ background:#ffedd5; color:#c2410c; }
        .status-badge--maladie{ background:#e0e7ff; color:#4338ca; }
        .status-badge--late{ background:#fef3c7; color:#b45309; }
        .status-badge--default{ background:#f1f5f9; color:#475569; }

        .page-head{
            display:flex;
            justify-content:space-between;
            align-items:flex-end;
            gap:18px;
            margin-bottom:22px;
        }

        .page-kicker{
            color:#64748b;
            font-size:13px;
            font-weight:750;
        }

        .page-heading{
            margin:0;
            font-size:28px;
            font-weight:900;
            letter-spacing:0;
            line-height:1.15;
        }

        .page-subtitle{
            margin-top:6px;
            color:#64748b;
            font-size:14px;
            font-weight:650;
        }

        .panel-head{
            display:flex;
            justify-content:space-between;
            align-items:center;
            gap:16px;
            margin-bottom:18px;
        }

        .section-title{
            margin:0;
            font-size:20px;
            font-weight:900;
        }

        .section-sub{
            color:#64748b;
            font-size:13px;
            font-weight:650;
        }

        .form-label{
            color:#334155;
            font-size:13px;
            font-weight:800;
            margin-bottom:8px;
        }

        .form-control,
        .form-select{
            border-radius:14px;
            border:1px solid #dbe3ef;
            font-weight:650;
        }

        .form-control:focus,
        .form-select:focus{
            border-color:#2563eb;
            box-shadow:0 0 0 4px rgba(37,99,235,0.10);
        }

        .control-lg{
            min-height:48px;
            padding:11px 14px;
        }

        .table-toolbar{
            display:flex;
            justify-content:space-between;
            align-items:center;
            gap:14px;
            flex-wrap:wrap;
            margin-bottom:18px;
        }

        .search-field{
            width:min(100%, 360px);
            min-height:48px;
            background:#fff;
        }

        .employee-cell{
            display:flex;
            flex-direction:column;
            gap:3px;
        }

        .employee-name{
            font-weight:850;
            color:#0f172a;
        }

        .employee-meta{
            color:#64748b;
            font-size:12px;
            font-weight:600;
        }

        .metric-grid{
            display:grid;
            grid-template-columns:repeat(4, minmax(0, 1fr));
            gap:18px;
            margin-bottom:24px;
        }

        .metric-card{
            position:relative;
            overflow:hidden;
            min-height:148px;
            padding:22px;
            border-radius:20px;
            border:1px solid rgba(226,232,240,0.95);
            background:#fff;
            box-shadow:0 18px 42px rgba(15,23,42,0.07);
        }

        .metric-card:after{
            content:"";
            position:absolute;
            right:-32px;
            top:-38px;
            width:112px;
            height:112px;
            border-radius:999px;
            opacity:.12;
            background:var(--metric-color, #2563eb);
        }

        .metric-top{
            display:flex;
            justify-content:space-between;
            align-items:center;
            gap:12px;
            margin-bottom:18px;
        }

        .metric-icon{
            display:grid;
            place-items:center;
            width:44px;
            height:44px;
            border-radius:14px;
            background:var(--metric-soft, #dbeafe);
            color:var(--metric-color, #2563eb);
        }

        .metric-number{
            font-size:40px;
            line-height:1;
            font-weight:950;
            letter-spacing:0;
            color:var(--metric-color, #0f172a);
        }

        .metric-card .metric-label{
            color:#475569;
            font-size:13px;
            font-weight:850;
            text-transform:none;
        }

        .metric-subtitle{
            margin-top:10px;
            color:#64748b;
            font-size:13px;
            font-weight:650;
        }

        .metric-present{ --metric-color:#15803d; --metric-soft:#dcfce7; }
        .metric-absent{ --metric-color:#b91c1c; --metric-soft:#fee2e2; }
        .metric-conge{ --metric-color:#c2410c; --metric-soft:#ffedd5; }
        .metric-repos{ --metric-color:#5b21b6; --metric-soft:#ede9fe; }
        .metric-primary{ --metric-color:#2563eb; --metric-soft:#dbeafe; }
        .metric-neutral{ --metric-color:#475569; --metric-soft:#f1f5f9; }

        .soft-panel{
            border-radius:18px;
            border:1px solid #eef2f7;
            background:#f8fafc;
            padding:16px;
        }

        .btn{
            border-radius:12px;
            font-weight:750;
        }

        @media (max-width: 900px){
            .sidebar{
                position:sticky;
                inset:auto;
                width:100%;
                display:flex;
                flex-wrap:wrap;
                gap:8px;
                padding:14px;
                border-right:0;
                border-bottom:1px solid var(--border);
            }

            .brand{
                width:100%;
                margin-bottom:4px;
            }

            .main{
                margin-left:0;
                padding:16px;
            }

            .topbar{
                align-items:flex-start;
                gap:14px;
                flex-direction:column;
            }

            .page-head,
            .panel-head{
                align-items:flex-start;
                flex-direction:column;
            }

            .metric-grid{
                grid-template-columns:1fr;
            }

            .search-field{
                width:100%;
            }
        }

        @media (min-width: 901px) and (max-width: 1200px){
            .metric-grid{
                grid-template-columns:repeat(2, minmax(0, 1fr));
            }
        }
    </style>
</head>

<body>

<?php
    $uiMessage = function (?string $message): string {
        return [
            'Invalid PIN code.' => 'Code PIN invalide.',
            'This employee account is inactive.' => 'Ce compte employé est inactif.',
            'Check-in recorded successfully.' => 'Entrée enregistrée avec succès.',
            'Check-out recorded successfully.' => 'Sortie enregistrée avec succès.',
            'Saved' => 'Enregistré.',
            'Absence saved successfully.' => 'Absence enregistrée avec succès.',
            'Absence deleted successfully.' => 'Absence supprimée avec succès.',
            'Employee created successfully.' => 'Employé créé avec succès.',
            'Employee updated successfully.' => 'Employé mis à jour avec succès.',
            'Employee deleted successfully.' => 'Employé supprimé avec succès.',
            'Weekly planning saved successfully.' => 'Planning hebdomadaire enregistré avec succès.',
            'Invalid planning role selected.' => 'Rôle de planning sélectionné invalide.',
            'The pin code field is required.' => 'Le code PIN est obligatoire.',
            'The selected action is invalid.' => 'L’action sélectionnée est invalide.',
            'The user id field is required.' => 'L’employé est obligatoire.',
            'The selected user id is invalid.' => 'L’employé sélectionné est invalide.',
            'The date field is required.' => 'La date est obligatoire.',
            'The date field must be a valid date.' => 'La date doit être valide.',
            'The check in time field must match the format H:i,H:i:s.' => 'L’heure d’entrée doit respecter le format HH:MM.',
            'The check out time field must match the format H:i,H:i:s.' => 'L’heure de sortie doit respecter le format HH:MM.',
            'The reason field is required.' => 'Le motif est obligatoire.',
            'The reason field must be a string.' => 'Le motif doit être du texte.',
            'The reason field must not be greater than 255 characters.' => 'Le motif ne doit pas dépasser 255 caractères.',
            'The name field is required.' => 'Le nom est obligatoire.',
            'The name field must be a string.' => 'Le nom doit être du texte.',
            'The name field must not be greater than 255 characters.' => 'Le nom ne doit pas dépasser 255 caractères.',
            'The email field must be a valid email address.' => 'L’adresse email doit être valide.',
            'The email field must not be greater than 255 characters.' => 'L’adresse email ne doit pas dépasser 255 caractères.',
            'The email has already been taken.' => 'Cette adresse email est déjà utilisée.',
            'The phone field must be a string.' => 'Le téléphone doit être du texte.',
            'The phone field must not be greater than 30 characters.' => 'Le téléphone ne doit pas dépasser 30 caractères.',
            'The position field must be a string.' => 'Le poste doit être du texte.',
            'The position field must not be greater than 120 characters.' => 'Le poste ne doit pas dépasser 120 caractères.',
            'The department field must be a string.' => 'Le département doit être du texte.',
            'The department field must not be greater than 120 characters.' => 'Le département ne doit pas dépasser 120 caractères.',
            'The status field is required.' => 'Le statut est obligatoire.',
            'The selected status is invalid.' => 'Le statut sélectionné est invalide.',
            'The hire date field must be a valid date.' => 'La date d’embauche doit être valide.',
            'The pin code field must not be greater than 20 characters.' => 'Le code PIN ne doit pas dépasser 20 caractères.',
            'The pin code has already been taken.' => 'Ce code PIN est déjà utilisé.',
            'The search field must be a string.' => 'La recherche doit être du texte.',
            'The search field must not be greater than 255 characters.' => 'La recherche ne doit pas dépasser 255 caractères.',
            'The month field must match the format Y-m.' => 'Le mois doit respecter le format AAAA-MM.',
            'The week field is required.' => 'La semaine est obligatoire.',
            'The schedule field must be an array.' => 'Le planning doit être une liste valide.',
        ][$message ?? ''] ?? (string) $message;
    };
?>

<!-- SIDEBAR -->
<div class="sidebar">
    <div class="brand">
        <span class="brand-mark">GA</span>
        <span>Gestion des Absences</span>
    </div>

    <a class="nav-link <?php echo e(request()->routeIs('admin.dashboard') ? 'active' : ''); ?>" href="<?php echo e(route('admin.dashboard')); ?>">Tableau de bord</a>
    <a class="nav-link <?php echo e(request()->routeIs('employees.*') ? 'active' : ''); ?>" href="<?php echo e(route('employees.index')); ?>">Employés</a>
    <a class="nav-link <?php echo e(request()->routeIs('attendance.*') ? 'active' : ''); ?>" href="<?php echo e(route('attendance.index')); ?>">Présences</a>
    <a class="nav-link <?php echo e(request()->routeIs('absences.*') ? 'active' : ''); ?>" href="<?php echo e(route('absences.index')); ?>">Absences</a>
    <a class="nav-link <?php echo e(request()->routeIs('planning.*') ? 'active' : ''); ?>" href="<?php echo e(route('planning.index')); ?>">Planning</a>
    <a class="nav-link <?php echo e(request()->routeIs('reports.*') ? 'active' : ''); ?>" href="<?php echo e(route('reports.index')); ?>">Rapports</a>
</div>

<!-- MAIN -->
<div class="main">

    <!-- TOPBAR -->
    <div class="topbar">
        <div class="topbar-brand">
            <span class="topbar-mark">GA</span>
            <div>
                <div class="app-eyebrow"><?php echo $__env->yieldContent('eyebrow','Gestion des Absences'); ?></div>
                <div class="page-title"><?php echo $__env->yieldContent('page-title','Tableau de bord'); ?></div>
            </div>
        </div>

        <form method="POST" action="<?php echo e(route('logout')); ?>">
            <?php echo csrf_field(); ?>
            <button class="btn btn-dark btn-sm">Déconnexion</button>
        </form>
    </div>

    <!-- ALERTS -->
    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e($uiMessage(session('success'))); ?></div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert alert-danger"><?php echo e($uiMessage(session('error'))); ?></div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
        <div class="alert alert-danger">
            <b>Veuillez corriger les erreurs</b>
            <ul class="mb-0">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($uiMessage($error)); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php echo $__env->yieldContent('content'); ?>

</div>

</body>
</html>
<?php /**PATH C:\xa\htdocs\gestion-d-present\presence-system\resources\views\layouts\app.blade.php ENDPATH**/ ?>
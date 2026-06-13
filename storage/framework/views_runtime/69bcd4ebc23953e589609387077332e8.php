<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion PIN | Gestion des Absences</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root{
            --text:#0f172a;
            --muted:#64748b;
            --border:#dbe3ef;
            --primary:#2563eb;
            --teal:#14b8a6;
        }

        body{
            min-height:100vh;
            margin:0;
            color:var(--text);
            background:
                radial-gradient(circle at 12% 12%, rgba(37,99,235,0.16), transparent 28%),
                radial-gradient(circle at 86% 18%, rgba(20,184,166,0.14), transparent 26%),
                linear-gradient(145deg, #f8fafc 0%, #eef3f8 54%, #f7f9fc 100%);
            display:grid;
            place-items:center;
            font-family:Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Arial, sans-serif;
            padding:28px;
        }

        .login-shell{
            width:min(100%, 520px);
        }

        .login-card{
            position:relative;
            overflow:hidden;
            background:rgba(255,255,255,0.94);
            border:1px solid rgba(226,232,240,0.96);
            border-radius:28px;
            padding:38px;
            box-shadow:0 28px 70px rgba(15,23,42,0.14);
            backdrop-filter:blur(18px);
        }

        .login-card:before{
            content:"";
            position:absolute;
            inset:0 0 auto;
            height:6px;
            background:linear-gradient(90deg, var(--primary), var(--teal), #f97316);
        }

        .brand-block{
            text-align:center;
            margin-bottom:30px;
        }

        .brand-mark{
            display:grid;
            place-items:center;
            width:64px;
            height:64px;
            margin:0 auto 16px;
            border-radius:20px;
            background:linear-gradient(135deg, var(--primary), var(--teal));
            color:#fff;
            font-size:22px;
            font-weight:950;
            box-shadow:0 16px 32px rgba(37,99,235,0.24);
        }

        .brand-name{
            margin:0;
            font-size:28px;
            font-weight:950;
            letter-spacing:0;
        }

        .brand-subtitle{
            margin:8px 0 0;
            color:var(--muted);
            font-size:15px;
            font-weight:650;
        }

        .form-label{
            color:#334155;
            font-size:13px;
            font-weight:850;
            margin-bottom:9px;
        }

        .pin-input{
            height:76px;
            text-align:center;
            font-size:32px;
            font-weight:950;
            letter-spacing:10px;
            border-radius:18px;
            border:1px solid var(--border);
            background:#f8fafc;
            box-shadow:inset 0 1px 0 rgba(255,255,255,0.8);
        }

        .pin-input:focus,
        .form-select:focus{
            border-color:var(--primary);
            box-shadow:0 0 0 4px rgba(37,99,235,0.12);
        }

        .form-select{
            height:54px;
            border-radius:16px;
            border:1px solid var(--border);
            font-weight:750;
        }

        .login-button{
            height:58px;
            border:0;
            border-radius:18px;
            background:linear-gradient(135deg, #2563eb, #1d4ed8);
            font-size:16px;
            font-weight:900;
            box-shadow:0 16px 30px rgba(37,99,235,0.24);
        }

        .login-button:hover{
            background:linear-gradient(135deg, #1d4ed8, #1e40af);
        }

        .alert{
            border-radius:16px;
            font-weight:700;
        }

        @media (max-width: 520px){
            body{
                padding:16px;
            }

            .login-card{
                padding:28px 22px;
                border-radius:24px;
            }

            .brand-name{
                font-size:24px;
            }

            .pin-input{
                height:70px;
                font-size:28px;
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
                'The pin code field is required.' => 'Le code PIN est obligatoire.',
                'The selected action is invalid.' => 'L’action sélectionnée est invalide.',
            ][$message ?? ''] ?? (string) $message;
        };
    ?>

    <main class="login-shell">
        <section class="login-card">
            <div class="brand-block">
                <div class="brand-mark">GA</div>
                <h1 class="brand-name">Gestion des Absences</h1>
                <p class="brand-subtitle">Connexion sécurisée par PIN</p>
            </div>

            <?php if(session('error')): ?>
                <div class="alert alert-danger"><?php echo e($uiMessage(session('error'))); ?></div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('pin.store')); ?>" autocomplete="off">
                <?php echo csrf_field(); ?>

                <div class="mb-4">
                    <label class="form-label" for="pin_code">Code PIN</label>
                    <input id="pin_code"
                           class="form-control pin-input <?php $__errorArgs = ['pin_code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           name="pin_code"
                           type="password"
                           inputmode="numeric"
                           autofocus
                           required>
                    <?php $__errorArgs = ['pin_code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($uiMessage($message)); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="mb-4">
                    <label class="form-label" for="action">Action</label>
                    <select id="action" class="form-select" name="action">
                        <option value="check_in">Entrée</option>
                        <option value="check_out">Sortie</option>
                    </select>
                </div>

                <button class="btn btn-primary login-button w-100" type="submit">Continuer</button>
            </form>
        </section>
    </main>
</body>
</html>
<?php /**PATH C:\xa\htdocs\gestion-d-present\presence-system\resources\views/pin-login.blade.php ENDPATH**/ ?>
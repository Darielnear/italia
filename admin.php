<?php
session_start();
require 'db_config.php';

// --- CONFIGURATION SÉCURITÉ ---
$admin_user = "Dariel";
$admin_pass = "Darielnear10";

// Déconnexion
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: admin.php");
    exit;
}

// Vérification de la connexion
if (isset($_POST['login'])) {
    if ($_POST['username'] === $admin_user && $_POST['password'] === $admin_pass) {
        $_SESSION['admin_logged_in'] = true;
    } else {
        $error = "Identifiants incorrects";
    }
}

// SI NON CONNECTÉ : Formulaire de Login Noir & Blanc
if (!isset($_SESSION['admin_logged_in'])): ?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Login | Cicli Volante</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#0f110f] flex items-center justify-center min-h-screen">
    <div class="max-w-md w-full p-8 bg-white rounded-[2.5rem] shadow-2xl">
        <div class="text-center mb-10">
            <h1 class="text-3xl font-black italic uppercase tracking-tighter">CICLI <span class="text-[#2D5A27]">VOLANTE</span></h1>
            <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-400 mt-2">Accès Atelier Privé</p>
        </div>
        <form method="POST">
            <div class="mb-4">
                <input type="text" name="username" placeholder="Nom d'utilisateur" class="w-full px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-2 focus:ring-[#2D5A27] transition-all" required>
            </div>
            <div class="mb-6">
                <input type="password" name="password" placeholder="Mot de passe" class="w-full px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-2 focus:ring-[#2D5A27] transition-all" required>
            </div>
            <?php if(isset($error)) echo "<p class='text-red-500 text-xs mb-4 text-center font-bold'>$error</p>"; ?>
            <button type="submit" name="login" class="w-full bg-black text-white font-black py-4 rounded-2xl uppercase text-[11px] tracking-widest hover:bg-[#2D5A27] transition-all shadow-lg shadow-gray-200">Se connecter</button>
        </form>
    </div>
</body>
</html>
<?php exit; endif; ?>

<?php
// --- SI CONNECTÉ : Dashboard de Luxe ---
$stmt = $pdo->query("SELECT * FROM orders ORDER BY created_at DESC");
$orders = $stmt->fetchAll();

$stmt_total = $pdo->query("SELECT SUM(totale) as grand_total FROM orders");
$ca_total = $stmt_total->fetch()['grand_total'];
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin | Cicli Volante</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;900&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-[#f8f9f8] min-h-screen pb-20">
    <nav class="bg-white border-b border-gray-100 px-8 py-4 mb-12 flex justify-between items-center sticky top-0 z-50">
        <h1 class="text-xl font-black italic uppercase tracking-tighter">CICLI <span class="text-[#2D5A27]">VOLANTE</span></h1>
        <a href="?logout=1" class="text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-red-500 transition-colors">Déconnexion</a>
    </nav>

    <div class="max-w-6xl mx-auto px-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
            <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100">
                <p class="text-[10px] font-black uppercase text-gray-400 tracking-widest mb-2">Chiffre d'Affaires</p>
                <p class="text-3xl font-black text-gray-900">€ <?= number_format($ca_total, 2, ',', '.') ?></p>
            </div>
            <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100 border-l-4 border-l-[#2D5A27]">
                <p class="text-[10px] font-black uppercase text-gray-400 tracking-widest mb-2">Commandes Total</p>
                <p class="text-3xl font-black text-gray-900"><?= count($orders) ?></p>
            </div>
            <div class="bg-[#2D5A27] p-8 rounded-[2rem] shadow-lg shadow-[#2d5a2733]">
                <p class="text-[10px] font-black uppercase text-white/60 tracking-widest mb-2">Statut Atelier</p>
                <p class="text-xl font-black text-white italic uppercase">Opérationnel</p>
            </div>
        </div>

        <div class="bg-white rounded-[2.5rem] shadow-xl shadow-gray-200/40 overflow-hidden border border-gray-50">
            <table class="w-full">
                <thead class="bg-gray-50/50 border-b border-gray-50">
                    <tr>
                        <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-gray-400 text-left">N° Commande</th>
                        <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-gray-400 text-left">Client</th>
                        <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-gray-400 text-left">Montant</th>
                        <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-gray-400 text-left">Paiement</th>
                        <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-gray-400 text-right">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php foreach ($orders as $o): ?>
                    <tr class="hover:bg-gray-50/30 transition-all">
                        <td class="px-8 py-8">
                            <span class="bg-[#2D5A27]/10 text-[#2D5A27] px-4 py-2 rounded-full text-[10px] font-black italic"><?= $o['order_number'] ?></span>
                        </td>
                        <td class="px-8 py-8">
                            <p class="font-bold text-gray-900"><?= $o['nome'] ?></p>
                            <p class="text-[11px] text-gray-400 font-medium"><?= $o['email'] ?></p>
                        </td>
                        <td class="px-8 py-8 font-black text-gray-900">€ <?= number_format($o['totale'], 2, ',', '.') ?></td>
                        <td class="px-8 py-8">
                            <?php if($o['ricevuta_path'] !== 'Nessun file'): ?>
                                <a href="<?= $o['ricevuta_path'] ?>" target="_blank" class="bg-black text-white px-5 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest hover:bg-[#2D5A27] transition-all inline-block shadow-md shadow-gray-200">
                                    Vérifier Reçu
                                </a>
                            <?php else: ?>
                                <span class="text-gray-300 text-[10px] font-bold uppercase italic">Manquant</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-8 py-8 text-right">
                            <p class="text-[11px] font-bold text-gray-400 italic"><?= date('d.m.Y', strtotime($o['created_at'])) ?></p>
                            <p class="text-[10px] text-gray-300"><?= date('H:i', strtotime($o['created_at'])) ?></p>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
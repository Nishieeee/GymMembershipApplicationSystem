<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gymazing | Profile</title>
    <script src="../public/assets/js/tailwindcss/tailwindcss.js"></script>
    <script src="../public/assets/js/jquery/jquery-3.7.1.min.js"></script>
    <link rel="stylesheet" href="../public/assets/icons/fontawesome/css/all.min.css">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #1a1a1a 0%, #2d3748 50%, #1a1a1a 100%);
        }
        * {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        ::-webkit-scrollbar { width: 10px; }
        ::-webkit-scrollbar-track { background: #1a1a1a; }
        ::-webkit-scrollbar-thumb { background: #1e3a8a; border-radius: 5px; }
        ::-webkit-scrollbar-thumb:hover { background: #1e40af; }
    </style>
</head>
<body class="gradient-bg min-h-screen">
    <?php include __DIR__ . '/layouts/navbar.php'; ?>

    <main class="pt-24 pb-12">
        <div class="container mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <div class="dashboard-section bg-neutral-900 rounded-xl p-8 border border-gray-700 shadow-lg mb-10 flex items-center">
                <div class="w-24 h-24 rounded-full bg-blue-400/20 flex items-center justify-center text-4xl font-bold text-blue-600 mr-8 border-4 border-blue-500 shadow-md">
                    <i class="fa-solid fa-user"></i>
                </div>
                <div>
                    <h2 class="text-3xl font-extrabold text-white mb-2 flex items-center">
                        <?= htmlspecialchars($userInfo['first_name'] . ' ' . $userInfo['last_name']) ?>
                    </h2>
                    <div class="flex items-center gap-3 mb-1">
                        <span class="inline-block px-3 py-1 rounded-full bg-blue-700/80 text-white text-xs uppercase tracking-wider">
                            <i class="fa-solid fa-user-tag mr-1"></i> <?= htmlspecialchars(ucfirst($role)) ?>
                        </span>
                        <span class="inline-block px-2 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold">
                            Member since <?= date('F Y', strtotime($userInfo['created_at'] ?? '')) ?>
                        </span>
                    </div>
                    <p class="text-blue-300 text-sm"><i class="fa-solid fa-envelope mr-2"></i><?= htmlspecialchars($userInfo['email']) ?></p>
                </div>
            </div>

            <?php if ($role === 'trainer'): ?>
            <section class="mb-10 grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="dashboard-section bg-gray-900 rounded-xl p-8 mb-8 border border-blue-950/50 shadow">
                    <h4 class="text-lg font-bold text-blue-400 mb-4 flex items-center"><i class="fa-solid fa-users mr-2"></i>Assigned Members</h4>
                    <?php if (!empty($assignedMembers)): ?>
                    <ul class="divide-y divide-gray-700 bg-blue-950/50 rounded-lg">
                        <?php foreach ($assignedMembers as $member): ?>
                            <li class="p-4 flex flex-col">
                                <span class="font-medium text-white"> <?= htmlspecialchars($member['name']) ?> </span>
                                <span class="text-blue-300 text-xs">Email: <?= htmlspecialchars($member['email']) ?></span>
                                <span class="text-xs bg-green-900/50 text-green-400 px-2 py-1 rounded mt-1 w-fit">Assigned: <?= date('M d, Y', strtotime($member['assigned_date'])) ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php else: ?>
                    <p class="text-gray-400">No members assigned.</p>
                    <?php endif; ?>
                </div>
                <div class="dashboard-section bg-gray-900 rounded-xl p-8 mb-8 border border-blue-950/50 shadow">
                    <h4 class="text-lg font-bold text-blue-400 mb-4 flex items-center"><i class="fa-solid fa-chalkboard-user mr-2"></i>Upcoming Sessions</h4>
                    <?php if (!empty($sessions)): ?>
                    <table class="min-w-full bg-neutral-800 rounded-md overflow-hidden">
                        <thead class="bg-blue-950/70">
                            <tr>
                                <th class="p-3 text-left text-blue-200">Session Date</th>
                                <th class="p-3 text-left text-blue-200">Member</th>
                                <th class="p-3 text-left text-blue-200">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($sessions as $session): ?>
                            <tr class="border-b border-gray-800 hover:bg-blue-900/30">
                                <td class="p-3 text-white">
                                    <i class="fa-regular fa-calendar mr-1 text-blue-400"></i>
                                    <?= date('M d, Y H:i', strtotime($session['session_date'])) ?>
                                </td>
                                <td class="p-3 text-blue-100">
                                    <?= htmlspecialchars($session['member_name']) ?>
                                </td>
                                <td class="p-3">
                                    <span class="inline-block px-2 py-1 rounded <?php
                                        if ($session['status']==='scheduled') echo 'bg-yellow-100/20 text-yellow-300';
                                        elseif ($session['status']==='completed') echo 'bg-green-100/20 text-green-400';
                                        else echo 'bg-gray-700 text-gray-300';
                                    ?>">
                                        <?= ucfirst($session['status']) ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php else: ?>
                    <p class="text-gray-400">No upcoming sessions.</p>
                    <?php endif; ?>
                </div>
            </section>
            <?php elseif ($role === 'member'): ?>
            <section class="dashboard-section bg-gray-900 rounded-xl p-8 border border-blue-950/50 shadow">
                <h4 class="text-lg font-bold text-blue-400 mb-4 flex items-center"><i class="fa-solid fa-dumbbell mr-2"></i>My Sessions</h4>
                <?php if (!empty($sessions)): ?>
                <table class="min-w-full bg-neutral-800 rounded-md overflow-hidden">
                    <thead class="bg-blue-950/70">
                        <tr>
                            <th class="p-3 text-left text-blue-200">Session Date</th>
                            <th class="p-3 text-left text-blue-200">Trainer</th>
                            <th class="p-3 text-left text-blue-200">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($sessions as $session): ?>
                        <tr class="border-b border-gray-800 hover:bg-blue-900/30">
                            <td class="p-3 text-white">
                                <i class="fa-regular fa-calendar mr-1 text-blue-400"></i>
                                <?= date('M d, Y H:i', strtotime($session['session_date'])) ?>
                            </td>
                            <td class="p-3 text-blue-100">
                                <?= htmlspecialchars($session['trainer_name']) ?>
                            </td>
                            <td class="p-3">
                                <span class="inline-block px-2 py-1 rounded <?php
                                    if ($session['status']==='scheduled') echo 'bg-yellow-100/20 text-yellow-300';
                                    elseif ($session['status']==='completed') echo 'bg-green-100/20 text-green-400';
                                    else echo 'bg-gray-700 text-gray-300';
                                ?>">
                                    <?= ucfirst($session['status']) ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <p class="text-gray-400">No sessions found.</p>
                <?php endif; ?>
            </section>
            <?php endif; ?>
        </div>
    </main>
    <?php include_once __DIR__ . '/layouts/footer.php'; ?>
</body>
</html>

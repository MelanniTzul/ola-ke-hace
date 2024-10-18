
<head>
    <link rel="stylesheet" type="text/css" href="/public/css/style_users.css">
</head>
<div id="usuarios" style="display:block;">
    <h1 class="nameUser">Usuarios</h1>
    <table>
        <thead>
            <tr>
                <th>Rol</th>
                <th>Nombre de Usuario</th>
                <th>ID Empleado</th>
                <th>ID Cliente</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><?php 
                if ($user["rol"] == 1) {
                    echo 'Administrador';
                }elseif($user['rol']==2){
                    echo 'Recepcinista';
                }elseif($user['rol']==3){
                    echo 'Cliente';
                }; 
                ?></td>
                <td><?php echo htmlspecialchars($user['user_name']); ?></td>
                <td><?php echo htmlspecialchars($user['id_empleado']); ?></td>
                <td><?php echo htmlspecialchars($user['id_cliente']); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

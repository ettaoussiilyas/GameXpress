@can('edit products')
    <!-- Only shown to users with 'edit products' permission -->
@endcan

@role('super-admin')
<!-- Only shown to users with 'super-admin' role -->
@endrole

// Check if user has role
$user->hasRole('admin');

// Check if user has permission
$user->hasPermissionTo('edit products');

// Assign role to user
$user->assignRole('admin');

// Remove role from user
$user->removeRole('admin');

// Give permission to role
$role->givePermissionTo('edit products');

// Revoke permission from role
$role->revokePermissionTo('edit products');

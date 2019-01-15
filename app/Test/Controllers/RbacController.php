<?php
namespace App\Test\Controllers;


use App\Permission;
use App\Post;
use App\Role;
use App\User;
use Illuminate\Support\Facades\Auth;
use Laratrust\Laratrust;

/**
 * RBAC 权限控制
 * Class RbacController
 * @package App\Test\Controllers
 */
class RbacController extends Controller
{
    /**
     * 创建角色
     */
    public function createRole()
    {
        $owner = new Role();
        $owner->name         = 'owner';
        $owner->display_name = 'Project Owner'; // optional
        $owner->description  = 'User is the owner of a given project'; // optional
        $owner->save();

        $admin = new Role();
        $admin->name         = 'admin';
        $admin->display_name = 'User Administrator'; // optional
        $admin->description  = 'User is allowed to manage and edit other users'; // optional
        $admin->save();
    }

    /**
     * 创建权限
     */
    public function createPermission()
    {
        $createPost = new Permission();
        $createPost->name         = 'create-post';
        $createPost->display_name = 'Create Posts'; // optional
        // Allow a user to...
        $createPost->description  = 'create new blog posts'; // optional
        $createPost->save();

        $editUser = new Permission();
        $editUser->name         = 'edit-user';
        $editUser->display_name = 'Edit Users'; // optional
        // Allow a user to...
        $editUser->description  = 'edit existing users'; // optional
        $editUser->save();
    }

    /**
     * 赋予角色权限
     * 删除角色权限
     */
    public function rolePermissions()
    {
        $createPost = Permission::find(1);
        $editUser = Permission::find(2);
        $admin = Role::find(1);
        $owner = Role::find(2);

        //attcach
        $admin->attachPermission($createPost); // parameter can be a Permission object, array or id
        // equivalent to $admin->permissions()->attach([$createPost->id]);

        $owner->attachPermissions([$createPost, $editUser]); // parameter can be a Permission object, array or id
        // equivalent to $owner->permissions()->attach([$createPost->id, $editUser->id]);

        $owner->syncPermissions([$createPost, $editUser]); // parameter can be a Permission object, array or id
        // equivalent to $owner->permissions()->sync([$createPost->id, $editUser->id]);

        //remove
        $admin->detachPermission($createPost); // parameter can be a Permission object, array or id
        // equivalent to $admin->permissions()->detach([$createPost->id]);

        $owner->detachPermissions([$createPost, $editUser]); // parameter can be a Permission object, array or id
        // equivalent to $owner->permissions()->detach([$createPost->id, $editUser->id]);
    }

    /**
     * 赋予用户角色
     * 删除用户角色
     */
    public function userRoles()
    {
        $user = User::find(1);
        $admin = Role::find(1);
        $owner = Role::find(2);

        //attach
        $user->attachRole($admin); // parameter can be a Role object, array, id or the role string name
        // equivalent to $user->roles()->attach([$admin->id]);

        $user->attachRoles([$admin, $owner]); // parameter can be a Role object, array, id or the role string name
        // equivalent to $user->roles()->attach([$admin->id, $owner->id]);

        $user->syncRoles([$admin->id, $owner->id]);
        // equivalent to $user->roles()->sync([$admin->id]);

        //remove
        $user->detachRole($admin); // parameter can be a Role object, array, id or the role string name
        // equivalent to $user->roles()->detach([$admin->id]);

        $user->detachRoles([$admin, $owner]); // parameter can be a Role object, array, id or the role string name
        // equivalent to $user->roles()->detach([$admin->id, $owner->id]);
    }

    /**
     * 给用户权限
     * 删除用户权限
     */
    public function userPermissions()
    {
        $user = User::find(1);
        $createPost = Permission::find(1);
        $editUser = Permission::find(2);

        //attach
        $user->attachPermission($editUser); // parameter can be a Permission object, array, id or the permission string name
        // equivalent to $user->permissions()->attach([$editUser->id]);

        $user->attachPermissions([$editUser, $createPost]); // parameter can be a Permission object, array, id or the permission string name
        // equivalent to $user->permissions()->attach([$editUser->id, $createPost->id]);

        $user->syncPermissions([$editUser->id, $createPost->id]);
        // equivalent to $user->permissions()->sync([$editUser->id, createPost->id]);

        //remove
        $user->detachPermission($createPost); // parameter can be a Permission object, array, id or the permission string name
        // equivalent to $user->roles()->detach([$createPost->id]);

        $user->detachPermissions([$createPost, $editUser]); // parameter can be a Permission object, array, id or the permission string name
        // equivalent to $user->roles()->detach([$createPost->id, $editUser->id]);
    }

    /**
     * 检查用户的 角色  权限
     * If you want, you can use the hasPermission and isAbleTo methods instead of the can method
     */
    public function CheckingForRolesAndPermissions()
    {
        $user = User::find(1);

        $user->hasRole('owner');   // false
        $user->hasRole('admin');   // true
        $user->can('edit-user');   // false
        $user->can('create-post'); // true

        //Both can() and hasRole() can receive an array or pipe separated string of roles & permissions to check
        $user->hasRole(['owner', 'admin']);       // true
        $user->can(['edit-user', 'create-post']); // true

        $user->hasRole('owner|admin');       // true
        $user->can('edit-user|create-post'); // true

        //By default, if any of the roles or permissions are present for a user then the method will return true.
        // Passing true as a second parameter instructs the method to require all of the items
        $user->hasRole(['owner', 'admin']);             // true
        $user->hasRole(['owner', 'admin'], true);       // false, user does not have admin role
        $user->can(['edit-user', 'create-post']);       // true
        $user->can(['edit-user', 'create-post'], true); // false, user does not have edit-user permission

        /*
         * 可以给用户角色 也可以直接给用户权限
         * You can have as many Roles as you want for each User and vice versa. Also,
         * you can have as many direct Permissionss as you want for each User and vice versa
         *
         * The Laratrust class has shortcuts to both can() and hasRole() for the currently logged in user:
         *
         * There aren’t Laratrust::hasPermission or Laratrust::isAbleTo facade methods,
         * because you can use the Laratrust::can even when using the Authorizable trait*/
        Laratrust::hasRole('role-name');
        Laratrust::can('permission-name');

        // is identical to

        Auth::user()->hasRole('role-name');
        Auth::user()->hasPermission('permission-name');
    }

    /**
     * You can check if a user has some permissions by using the magic can method:
     */
    public function magicCanMethod()
    {
        $user = User::find(1);

        $user->canCreateUsers();
        // Same as $user->can('create-users');

        /*
         * If you want to change the case used when checking for the permission,
         * you can change the magic_can_method_case value in your config/laratrust.php file.*/
        // config/laratrust.php
        //'magic_can_method_case' => 'snake_case',
        // The default value is 'kebab_case'

        // In you controller
        $user->canCreateUsers();
        // Same as $user->can('create_users');
    }

    /**
     *高级检查
     */
    public function userAbility()
    {
        /*
         * More advanced checking can be done using the awesome ability function.
         * It takes in three parameters (roles, permissions, options):

        roles is a set of roles to check.
        permissions is a set of permissions to check.
        options is a set of options to change the method behavior.
        Either of the roles or permissions variable can be a comma separated string or array:*/
        $user = User::find(1);


        $user->ability(['admin', 'owner'], ['create-post', 'edit-user']);
        // or
        $user->ability('admin,owner', 'create-post,edit-user');
        /*
         * This will check whether the user has any of the provided roles and permissions.
         * In this case it will return true since the user is an admin and has the create-post permission*/

        /*The third parameter is an options array:
        validate_all is a boolean flag to set whether to check all the values for true,
        or to return true if at least one role or permission is matched.
        return_type specifies whether to return a boolean, array of checked values, or both in an array.*/
        $options = [
//            'validate_all' => true|false(Default: false),
//            'return_type'  => boolean|array|both(Default: boolean)
        ];

        $options = [
            'validate_all' => true,
            'return_type' => 'both'
        ];

        list($validate, $allValidations) = $user->ability(
            ['admin', 'owner'],
            ['create-post', 'edit-user'],
            $options
        );

        var_dump($validate);
        // bool(false)

        var_dump($allValidations);
        // array(4) {
        //     ['role'] => bool(true)
        //     ['role_2'] => bool(false)
        //     ['create-post'] => bool(true)
        //     ['edit-user'] => bool(false)
        // }

        /*The Laratrust class has a shortcut to ability() for the currently logged in user:*/
        Laratrust::ability('admin,owner', 'create-post,edit-user');

        // is identical to

        Auth::user()->ability('admin,owner', 'create-post,edit-user');
    }

    /**
     * 获取全部权限 角色
     */
    public function retrievingRelationships()
    {
        /*
         * The LaratrustUserTrait has the roles and permissions relationship,
         * that return a MorphToMany relationships.

        The roles relationship has all the roles attached to the user.

        The permissions relationship has all the direct permissions attached to the user.

        If you want to retrieve all the user permissions, you can use the allPermissions method.
        It returns a unified collection with all the permissions related to the user (via the roles and permissions relationships).*/

        $user = User::find(1);

        dump($user->allPermissions());
        /*
         Illuminate\Database\Eloquent\Collection {#646
          #items: array:2 [
            0 => App\Permission {#662
              ...
              #attributes: array:6 [
                "id" => "1"
                "name" => "edit-users"
                "display_name" => "Edit Users"
                "description" => null
                "created_at" => "2017-06-19 04:58:30"
                "updated_at" => "2017-06-19 04:58:30"
              ]
              ...
            }
            1 => App\Permission {#667
              ...
              #attributes: array:6 [
                "id" => "2"
                "name" => "manage-users"
                "display_name" => "Manage Users"
                "description" => null
                "created_at" => "2017-06-19 04:58:30"
                "updated_at" => "2017-06-19 04:58:30"
              ]
              ...
            }
          ]
        }
         */


        /*
         * If you want to retrieve the users that have some role you can use the query scope whereRoleIs:*/
        // This will return the users with 'admin' role.
        $users = User::whereRoleIs('admin')->get();

        /*
         * Also, if you want to retrieve the users that have some permission you can use the query scope wherePermissionIs*/
        // This will return the users with 'edit-user' permission.
        $users = User::wherePermissionIs('edit-user')->get();
    }

    /**
     * 检查对象的主人
     */
    public function objectsOwnership()
    {
        /*If you need to check if the user owns an object you can use the user function owns:*/
        $user = User::find(1);
        $post = Post::find(1);
        if ($user->owns($post)) {
            //This will check the 'user_id' inside the $post
            abort(403);
        }

        /*If you want to change the foreign key name to check for, you can pass a second attribute to the method:*/
        if ($user->owns($post, 'idUser')) { //This will check for 'idUser' inside the $post
            abort(403);
        }
    }
}
?>
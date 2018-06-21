<?php

use Illuminate\Database\Seeder;

use App\Models\Auth\User;
use App\Models\Auth\Role;
use App\Models\Auth\Permission;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Очищаем таблицы базовых моделей
		User::truncate();
        Role::truncate();
    	Permission::truncate();
		
    	//Очищаем таблицы межмодельной связи
    	DB::table('permission_role')->truncate();
    	DB::table('role_user')->truncate();

        //Создаём дефолтные роли
        $administratorRole = new Role();
		$administratorRole->name         = 'administrator';
		$administratorRole->display_name = 'Main Administrator';
		$administratorRole->description  = 'User is allowed to manage and edit other users'; 
		$administratorRole->save();
		
		$operatorRole = new Role();
		$operatorRole->name = 'operator';
		$operatorRole->display_name = 'Operator';
		$operatorRole->description = 'Operator is moderator. With restricted access to users list';
		$operatorRole->save();
		
		/*
		 Администратор права:
		 -все права
		 -дозволено полное оперирование списком пользователей типа "operator"
		 
		 Оператор:
		 -дозволено видеть соседних операторов
		 -нельзя 
		 
		 ! Переделать массив присваивания затем
		 
		*/
		
        //Создаём дефолтные разрешения
        $permissions = [
			//Для роли "Главный администратор" будут такие права
        	[
        		'name' => 'users-fulleditor',
        		'display_name' => 'Full Editor of Users List',
        		'description' => 'User who are allowed to manipulate of users (create, read, update, delete)'
        	],
			
			//Для роли "Оператор" будет вот такое разрешение
        	[
        		'name' => 'user-readonly',
        		'display_name' => 'Read Only Users',
        		'description' => 'User who are allowed only to see another users'
        	],
        ]; //end $permissions definitor

        foreach ($permissions as $key => $value) {
        	$permission = new Permission();
			$permission->name         = $value['name'];
			$permission->display_name = $value['display_name']; 
			$permission->description  = $value['description'];
			$permission->save();
			
			// Цепляем разрешения к ролям
			if ($key == 0) {
				$administratorRole->attachPermission($permission);
			} elseif ($key == 1) {
				$operatorRole->attachPermission($permission);
			}
        }

        // Создаём дефолтных пользователей: админа и оператора
        $adminUser = new User();
        $adminUser->name = 'admin';
        $adminUser->email = 'admin@system.com';
        $adminUser->password = bcrypt('admin');
        $adminUser->remember_token = str_random(10);
        $adminUser->save();
        $adminUser->attachRole($administratorRole);
		
		
		$operatorUser = new User();
        $operatorUser->name = 'operator';
        $operatorUser->email = 'operator@system.com';
        $operatorUser->password = bcrypt('operator');
        $operatorUser->remember_token = str_random(10);
        $operatorUser->save();
        $operatorUser->attachRole($operatorRole);
		
    }
}

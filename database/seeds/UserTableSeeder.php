<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Persona;
use App\Role;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role_user = Role::where('name', 'user')->first();
        $role_admin = Role::where('name', 'admin')->first();

        $user = new User();
        $user->name = 'User';
        $user->rut = '987654321';
        $user->email = 'user@example.com';
        $user->password = bcrypt('secret');
        $user->save();
        $user->roles()->attach($role_user);

        $persona=new Persona();
        $persona->nombre='User';
        $persona->situacion_laboral='temp';
        $persona->rut=000000001;
        $persona->genero='bot';
        $persona->fecha_nacimiento='2019-01-26';
        $user->persona()->save($persona);

        $user = new User();
        $user->name = 'Admin';
        $user->rut = '123456789';
        $user->email = 'admin@example.com';
        $user->password = bcrypt('secret');
        $user->save();
        $user->roles()->attach($role_admin);

        $persona=new Persona();
        $persona->nombre='Admin';
        $persona->situacion_laboral='temp';
        $persona->rut=00000000;
        $persona->genero='Dios';
        $persona->fecha_nacimiento='2019-01-26';
        $user->persona()->save($persona);
    }
}

<?php

use Illuminate\Database\Seeder;

class ComentariosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('comentarios')->insert([
             'produto_id'=>1,
             'usuario'=>'João Lucas',
             'comentario'=>'Melho batata',
             'created_at'=>date("Y/m/d h:i:s"),
             'updated_at'=>date("Y/m/d h:i:s")
        ])
                //
                DB::table('comentarios')->insert([
                    'produto_id'=>1,
                    'usuario'=>'Maria',
                    'comentario'=>'Melho batata',
                    'created_at'=>date("Y/m/d h:i:s"),
                    'updated_at'=>date("Y/m/d h:i:s")
               ])
                       //
        DB::table('comentarios')->insert([
            'produto_id'=>1,
            'usuario'=>'Alberto',
            'comentario'=>'Melho batata',
            'created_at'=>date("Y/m/d h:i:s"),
            'updated_at'=>date("Y/m/d h:i:s")
       ])
               //
               DB::table('comentarios')->insert([
                'produto_id'=>1,
                'usuario'=>'Noire',
                'comentario'=>'Melhor batata',
                'created_at'=>date("Y/m/d h:i:s"),
                'updated_at'=>date("Y/m/d h:i:s")
           ])
    }
}

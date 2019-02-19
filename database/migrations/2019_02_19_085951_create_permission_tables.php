<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableNames = config('permission.table_names');
        $columnNames = config('permission.column_names');

        // 權限
        Schema::create($tableNames['permissions'], function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('guard_name');
            $table->timestamps();
        });

        // 角色
        Schema::create($tableNames['roles'], function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('guard_name');
            $table->timestamps();
        });

        // 一個Model能擁有多個權限
        Schema::create($tableNames['model_has_permissions'], function (Blueprint $table) use ($tableNames, $columnNames) {
            $table->unsignedInteger('permission_id');

            $table->string('model_type');
            $table->unsignedBigInteger($columnNames['model_morph_key']);
            $table->index([$columnNames['model_morph_key'], 'model_type', ]);

            $table->foreign('permission_id')
                ->references('id')
                ->on($tableNames['permissions'])
                ->onDelete('cascade');

            $table->primary(['permission_id', $columnNames['model_morph_key'], 'model_type'],
                    'model_has_permissions_permission_model_type_primary');
        });

        // 使用者擁有什麼角色在此表中定義，一個使用者可以擁有多個角色
        Schema::create($tableNames['model_has_roles'], function (Blueprint $table) use ($tableNames, $columnNames) {
            $table->unsignedInteger('role_id');

            $table->string('model_type');
            $table->unsignedBigInteger($columnNames['model_morph_key']);
            $table->index([$columnNames['model_morph_key'], 'model_type', ]);

            $table->foreign('role_id')
                ->references('id')
                ->on($tableNames['roles'])
                ->onDelete('cascade');

            $table->primary(['role_id', $columnNames['model_morph_key'], 'model_type'],
                    'model_has_roles_role_model_type_primary');
        });

        // 角色擁有的權限關聯表，如管理員擁有查看後台的權限都是在此定義，一個角色能擁有多個權限
        Schema::create($tableNames['role_has_permissions'], function (Blueprint $table) use ($tableNames) {
            $table->unsignedInteger('permission_id');
            $table->unsignedInteger('role_id');

            $table->foreign('permission_id')
                ->references('id')
                ->on($tableNames['permissions'])
                ->onDelete('cascade');

            $table->foreign('role_id')
                ->references('id')
                ->on($tableNames['roles'])
                ->onDelete('cascade');

            $table->primary(['permission_id', 'role_id']);

            app('cache')
                ->store(config('permission.cache.store') != 'default' ? config('permission.cache.store') : null)
                ->forget(config('permission.cache.key'));
        });
    }

    public function down()
    {
        $tableNames = config('permission.table_names');

        Schema::drop($tableNames['role_has_permissions']);
        Schema::drop($tableNames['model_has_roles']);
        Schema::drop($tableNames['model_has_permissions']);
        Schema::drop($tableNames['roles']);
        Schema::drop($tableNames['permissions']);
    }
}

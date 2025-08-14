<?php

namespace App\Http\RepositoryAll\Superadm\Role;
use Illuminate\Http\Request;
use App\Models\Roles;
use DB;

class RoleRepository
{
	public function list()
    {
        $result= Roles::where('is_active', 1)
                        ->orderBy('id', 'desc')
                        ->get();
        return $result;
    }
    
    public function save($data)
    {
    	return Roles::create($data);
    }

    public function edit($id)
    {
        $result= Roles::where('id', $id)->first();
        return $result;
    }

    public function update($data, $id)
    {
    	return Roles::where('id', $id)->update($data);
    }


    public function delete($data, $id)
    {
    	return Roles::where('id',$id )->update($data);
    }

}
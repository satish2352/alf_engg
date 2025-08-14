<?php
namespace App\Http\ServiceAll\Superadm\Role;
use Illuminate\Http\Request;
use App\Http\RepositoryAll\Superadm\Role\RoleRepository;
use Session;

class RoleService
{
    public function __construct()
    {
    	$this->repo=new RoleRepository();
    }

    public function list()
    {
    	return $this->repo->list();
    }


    public function save($req)
    {
    	$data=[ 'role'=>$req->input('role')];
		$result=$this->repo->save($data);
        return $result;
    }


    public function edit($id)
    {
        $result=$this->repo->edit($id);
        return $result;
    }


    public function update($req)
	{

        $id = $req->id;
        $data=['role'=>$req->input('role'),
               'is_active'=> $req->is_active
              ];

		$result=$this->repo->update($data, $id);
		return $result;

	}


	public function delete($req)
	{
        $id = base64_decode($req->id);;
        $data=['is_active'=> 0 ];
		$result=$this->repo->delete($data, $id);

		return $result;

	}


}
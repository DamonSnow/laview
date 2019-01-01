<?php

namespace App\Http\Controllers\Api;

use App\Handlers\MyTree;
use App\Models\Branch;
use Illuminate\Http\Request;
use App\Http\Resources\Branch AS BranchResource;
use App\Http\Controllers\Controller;

class BranchController extends Controller
{
    public function index()
    {
        $branches = MyTree::makeTree(Branch::all(),array(
            'expanded' => true
        ));
        return $branches[0];
    }

}

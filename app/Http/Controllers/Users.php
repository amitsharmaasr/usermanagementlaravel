<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User as User;
use Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Session;
use App\PermissionList; 
use App\Imports\UserImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Contracts\Validation\Factory as ValidatorFactory;

class Users extends Controller
{
    protected $user;
    public function __construct()
    {
       $this->middleware('auth');
       $this->middleware(function ($request, $next) {
        if(!isAuthorized('can_manage_user'))
        {
            abort(403);
        }
            return $next($request);
        });
    }

    public function users()
    {
        $user = User::all();
        return view('admin.users')->with(['users'=>$user, 'title'=>'All Users']);
    }

    public function create(Request $req)
    {
        if($req->has('create'))
        {
            $validateData = $req->validate([
                'name'=>['required', 'min:5', 'max:50'],
                'email'=>['required', 'unique:users'],
                'password'=>['required', 'min:6', 'max:20', 'confirmed'],
                'password_confirmation'=>['required', 'min:6', 'max:20'],
                'user_type' =>['required'],
                'dob'=>['required'],
                'doj'=>['required'],
                'picture'=>['required'],
                'gender'=>['required']
            ]);
            $picture = $req->file('picture');
            $destinationPath = public_path('/avtars');
            $new_name = date('Ymdhis').'_'.rand().'_'.$picture->getClientOriginalExtension(); 
            $picture->move($destinationPath, $new_name);
            $user = new User;
            $user->name = $req->name;
            $user->email = $req->email;
            $user->password = Hash::make($req->password);
            $user->user_type = $req->user_type;
            $user->dob = $req->dob;
            $user->doj = $req->doj;
            $user->gender = $req->gender;
            $user->picture = $new_name;
            $user->mail_password = $req->password;
            $user->save();
            $user_id = $user->id;
            Session::flash('success', 'User Added successfully!');
            if($req->user_type == 2)
            {
                $permission =$this->savePermission($user_id, $req->permission);
            }

            return redirect('users');
        }
        else
        {
            $permission_list = PermissionList::all()->groupBy('category')->toArray();
            return view('admin.create')->with(['title'=>'Create New User', 'permission_list'=>$permission_list]);
        }
    }

    private function savePermission($user_id,$permission)
    {
        deleteData('permission', ['user_id'=>$user_id]);
        $data = array();
        if(!empty($permission))
        {
            foreach($permission as $per)
            {
                $arr = array(
                    "user_id"=>$user_id,
                    "key" =>$per,
                    "value"=>1
                );
                $data[] = $arr;
            }
        }
        if(!empty($data))
        {
        insertData('permission', $data);
        }
        return true;
    }

    public function edit($id)
    {
        if(!checkAdminPermission($id))
        {
            abort(403);
        }
       $user = User::where('id', $id)
            ->get()
            ->toArray();

        if(empty($user))
        {
            abort(403);
        }
        else
        {
            $permission_list = PermissionList::all()->groupBy('category')->toArray();
            $permission_arr = getData('permission', ['user_id'=>$id, 'value'=>1]);
            $permission = array_column($permission_arr, 'key');
            return view('admin.edit')->with(['user'=>$user[0], 'permission'=>$permission, 'title'=>'Edit User', 'permission_list'=>$permission_list]);
        }
    }

    public function update(Request $req)
    {
        if($req->has('edit'))
        {
            $id = $req->input('id');
            if(!checkAdminPermission($id))
            {
                abort(403);
            }

            if(!empty($req->input('password')))
            {
                $validateData = $req->validate([
                    'name'=>['required', 'min:5', 'max:50'],
                    'user_type' =>['required'],
                    'password'=>['min:6', 'max:20', 'confirmed'],
                    'password_confirmation'=>['required', 'min:6', 'max:20'],
                    'dob'=>['required'],
                    'doj'=>['required'],
                    'gender'=>['required']
                ]);

                if($req->user_type == 2)
                {
                    $permission =$this->savePermission($id, $req->permission);
                }

                $arr = array(
                    'name' => $req->input('name'),
                    'user_type' => $req->input('user_type'),
                    'password' => Hash::make($req->input('password')),
                    'mail_password' => $req->password,
                    'dob' => $req->dob,
                    'doj' => $req->doj,
                    'gender' =>$req->gender,
                );

                $picture = $req->file('picture');

                if ($picture != "") {
                    $destinationPath = public_path('/avtars');
                    $new_name = date('Ymdhis').'_'.rand().'_'.$picture->getClientOriginalExtension(); 
                    $picture->move($destinationPath, $new_name);
                    $arr['picture'] = $new_name;
                }
                

                try{
                    User::findOrFail($id)->update($arr);
                    Session::flash('success', 'User updated successfully!');
                }
                catch(ModelNotFoundException $err)
                {
                    Session::flash('success', 'User not found to update!');
                }
            }
            else
            {
                $validateData = $req->validate([
                    'name'=>['required', 'min:5', 'max:50'],
                    'user_type' =>['required'],
                    'dob'=>['required'],
                    'doj'=>['required'],
                    'gender'=>['required']
                ]);

                if($req->user_type == 2)
                {
                    $permission =$this->savePermission($id, $req->permission);
                }

                $arr = array(
                    'name' => $req->input('name'),
                    'user_type' => $req->input('user_type'),
                    'dob' => $req->dob,
                    'doj' => $req->doj,
                    'gender' =>$req->gender,
                );

                $picture = $req->file('picture');
                if ($picture != "") {
                    $destinationPath = public_path('/avtars');
                    $new_name = date('Ymdhis').'_'.rand().'_'.$picture->getClientOriginalExtension(); 
                    $picture->move($destinationPath, $new_name);
                    $arr['picture'] = $new_name;
                }

                try{
                    User::findOrFail($id)->update($arr);
                    Session::flash('success', 'User updated successfully!');
                }
                catch(ModelNotFoundException $err)
                {
                    Session::flash('success', 'User not found to update!');
                }
            }
            return redirect('users');
        }
        else
        {
            abort(404);
        }
    }

    public function delete($id)
    {
        if(!isAuthorized('can_delete_user') || !checkAdminPermission($id))
        {
            abort(403);
        }

        try{
            $del = User::findOrFail($id)->delete();
            if($del)
            {
                Session::flash('success', 'User deleted successfully!');
                return redirect()->back();
            }
        }
        catch(ModelNotFoundException $err)
        {
            Session::flash('success', 'User Not Found!');
            return redirect()->back();
        }
    }

    public function exportCSV(Request $request)
    {
	   $fileName = 'users.csv';
	   $users = User::all();

	   $headers = array(
           	    "Content-type"        => "text/csv",
	            "Content-Disposition" => "attachment; filename=$fileName",
	            "Pragma"              => "no-cache",
        	    "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
	            "Expires"             => "0"
        );

        $columns = array('Name', 'Date of Birth', 'Date of Joining', 'Gender', 'designation', 'Email');

           $callback = function() use($users, $columns) {
           $file = fopen('php://output', 'w');
           fputcsv($file, $columns);

            foreach ($users as $user) {

                 switch ($user->user_type) {
                    case 2: 
                        $designation = 'Manager';
                        break;
                    case 1: 
                        $designation = 'Admin';
                        break;
                    default: 
                        $designation = 'Employee';
                        break;
                }

                $row['name']  = $user->name;
                $row['dob']    = $user->dob;
                $row['doj']    = $user->doj;
                $row['gender']  = $user->gender;
                $row['email']  = $user->email;
                $row['designation'] = $designation;
                fputcsv($file, array($row['name'], $row['dob'] , $row['doj'], $row['gender'], $row['designation'], $row['email']));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function bulkUploadUsers(Request $request, ValidatorFactory $validator)
    {
        $request->validate([
            'user_list' => 'required',
        ]);

        if ($request->hasFile('user_list')) {
            $file = $request->file('user_list');
            $filePath = $file->getPathname();
        
            try {
                $import = new UserImport($validator);
                Excel::import($import, $filePath, null, \Maatwebsite\Excel\Excel::CSV);
                $validationErrors = $import->getValidationErrors();
                if (!empty($validationErrors)) {
                    $errorMessages = [];

                    foreach ($validationErrors as $error) {
                        $errorMessages[] = "Error in row " . $error['row_num'] . ": " . implode(", ", $error['errors']);
                    }

                    Session::flash('error', implode("\n", $errorMessages));
                } else {
                    Session::flash('success', 'Upload Success.');
                }
            } catch (\Exception $e) {
                    Session::flash('success', 'Facing some error. Please try after some time.');
            }           
        } else {
            Session::flash('success', 'Please upload a CSV file.');
        }
        return redirect()->back();
    }


    public function bulkUploadView()
    {
        return view('admin.bulk-upload');
    }

    public function getmail(Request $request, $id)
    {
       
            $user = User::where('id', $id)
                ->get()
                ->toArray();

            if(empty($user)) {
                abort(403);
            } else {

                 try {
                $oClient = \Webklex\IMAP\Facades\Client::make([
                    'host'          => 'imap.gmail.com',
                    'port'          => 993,
                    'encryption'    => 'ssl',
                    'validate_cert' => true,
                    'username'      => $user[0]['email'],
                    'password'      => $user[0]['mail_password'],
                    'protocol'      => 'imap'
                ]);

            $oClient->connect();

            $folders = $oClient->getFolders();

            $page = 1;

            if ($request->has('page')) {
                    $page = $request->input('page');
            }

            foreach($folders as $folder){
                    if ($folder->name == "INBOX") {
                            $oMessage = $folder->messages()->all()->limit(10, $page)->setFetchOrder("desc")->get();
                            break;
                    }
            }

            $mails = [];

            foreach ($oMessage as $key => $value) {
                $message = [
                    'getDate' => $message->getDate(),
                    'getSubject' => $message->getSubject(),
                    'mail' => $message->getFrom()[0]->mail,
                    'htmlBody' => $message->getHTMLBody(true),
                    'attachment' => $message->getAttachments()
                ];

                array_push($mails, $message);
            }
            dd($mails);

             $attachmentPath = 'path/to/your/directory/' . $attachment->getName();
                        file_put_contents($attachmentPath, $attachment->g

            return view('admin.email')->with(['title'=>'User Inbox', 'oMessage'=>$oMessage]);
             } catch(\Exception $err) {
            Session::flash('success', 'Connection Refused with your gmail!');
            return redirect()->back();
        }
            }
       
    }

    
}

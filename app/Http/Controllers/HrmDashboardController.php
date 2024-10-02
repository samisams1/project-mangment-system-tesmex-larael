<?php  

namespace App\Http\Controllers;  

use Illuminate\Http\Request;
use App\Models\LaborType;
use App\Models\Labor;
use App\Models\ResourceRequest;
class HrmDashboardController extends Controller  
{  
    public function index()  
    {  
        $laborPossitions = LaborType::all();
        $labor = Labor::all();
        $allocatedLabor = Labor::where('status','allocate')->get();
        $unallocatedLabor = Labor::where('status','unallocated')->get();

        $pendingResourceRequest = ResourceRequest::where('status','Pending')->where('type','labor')->get();
        $approvedResourceRequest = ResourceRequest::where('status','approved')->where('type','labor')->get();
   
        $totalPendingRequest  = count($pendingResourceRequest);
        $totalApprovedRequest  = count($approvedResourceRequest);
        $total_labor = count($labor);


        // Pass the data to the view  
        return view('hrm.dashboard', [  
            'total_labor' => $total_labor,
            'newRequest' => $total_labor,
            'totalPendingRequest' => $totalPendingRequest,
            'totalApprovedRequest' =>$totalApprovedRequest,
            'laborPossitions'=>$laborPossitions,
            'allocatedLabor' =>  count($allocatedLabor),
            'unallocatedLabor' =>count($unallocatedLabor)
        ]);  


    }  
    public function list()
{
    $search = request('search');
    $sort = request('sort') ?? 'id';
    $order = request('order') ?? 'DESC';

    $priority = Labor::with('LaborType')->orderBy($sort, $order);

    if ($search) {
        $priority = $priority->where(function ($query) use ($search) {
            $query->where('title', 'like', '%' . $search . '%')
                  ->orWhere('id', 'like', '%' . $search . '%');
        });
    }

    $total = $priority->count();

    $priority = $priority->paginate(request('limit'))->through(
        fn ($priority) => [
            'id' => $priority->id,
            'name' => $priority->first_name . ' ' . $priority->last_name, // Assuming you meant last_name here
            'position' => $priority->LaborType->labor_type_name ?? null,
            'hourly_rate'=> $priority->LaborType->hourly_rate . " " ."Birr" ?? null, 
            'status' => $priority->status,
            'availability' => $priority->LaborType->availability ?? null, 
            'created_at' => format_date($priority->created_at, true),
            'updated_at' => format_date($priority->updated_at, true),
        ]
    );

    return response()->json([
        'rows' => $priority->items(),
        'total' => $total, // Changed to return the total count instead of the paginated object
    ]);
}
}
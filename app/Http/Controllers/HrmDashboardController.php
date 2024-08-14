<?php  

namespace App\Http\Controllers;  

use Illuminate\Http\Request;  

class HrmDashboardController extends Controller  
{  
    public function index()  
    {  
        // Fetch data for the HRM dashboard  
        $cards = [  
            [  
                'icon' => 'bx-briefcase-alt-2',  
                'title' => '333',  
                'subtitle' => get_label('Total Items', 'Total Items'),  
                'footerLabel' => get_label('Total Employees', 'Total Employees'),  
                'footerValues' => ['Full-time: 1,450', 'Part-time: 118',
                ]  
            ],  
            [  
                'icon' => 'bx-task',  
                'title' => '205',  
                'subtitle' => get_label('New Hires (YTD)', 'New Hires (YTD)'),  
                'footerLabel' => get_label('Average', 'Average'),  
                'footerValues' => ['Average Time to Hire: 42 days']  
            ],  
            [  
                'icon' => 'bxs-user-detail',  
                'title' => '7.2%',  
                'subtitle' => get_label('Turnover Rate', 'Turnover Rate'),  
                'footerLabel' => get_label('Involuntary', 'Involuntary'),  
                'footerValues' => ['Voluntary: 5.1%', 'Involuntary: 2.1%']  
            ],  
            [  
                'icon' => 'bxs-user-detail',  
                'title' => '52',  
                'subtitle' => get_label('High Potential Employees', 'High Potential Employees'),  
                'footerLabel' => get_label('Succession', 'Succession'),  
                'footerValues' => ['Succession Plan Coverage: 85%']  
            ]  
        ];  

        $statCards = [  
            [  
                'title' => '93%',  
                'subtitle' => get_label('Training Completion', 'Training Completion'),  
                'footer' => [  
                    get_label('Mandatory Training', 'Mandatory Training') => '97%',  
                    get_label('Voluntary Training', 'Voluntary Training') => '88%'  
                ]  
            ],  
            [  
                'title' => '$18.2M',  
                'subtitle' => get_label('Compensation Budget', 'Compensation Budget'),  
                'footer' => [  
                    get_label('Utilization', 'Utilization') => '97%'  
                ]  
            ],  
            [  
                'title' => '4.3/5',  
                'subtitle' => get_label('Employee Engagement', 'Employee Engagement'),  
                'footer' => [  
                    get_label('Satisfaction', 'Satisfaction') => '84%',  
                    get_label('Voluntary Turnover', 'Voluntary Turnover') => '5.9%'  
                ]  
            ]  
        ];  

        // Pass the data to the view  
        return view('hrm.dashboard', [  
            'cards' => $cards,  
            'statCards' => $statCards  
        ]);  
    }  
}
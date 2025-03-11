<?php

namespace App\Http\Controllers;

use App\Models\BackCharge;
use App\Models\ChangeOrder;
use App\Models\Client;
use App\Models\Employee;
use App\Models\Project;
use App\Models\PunchList;
use App\Models\Task;
use App\Models\Tender;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{

    protected $fields = [];
    protected $result = [];


    public function index(Request $rq)
    {

        switch ($rq->type) {
            case 'User':
                $this->result = $this->searchUser($rq->term);
                break;

            case 'Employee':
                $this->result = $this->searchEmployee($rq->term);
                break;

            case 'Client':
                $this->result = $this->searchClient($rq->term);
                break;

            case 'Tender':
                $this->result = $this->searchTender($rq->term);
                break;

            case 'Project':
                $this->result = $this->searchProject($rq->term);
                break;

            case 'Task':
                $this->result = $this->searchTask($rq->term);
                break;

            case 'Ticket':
                $this->result = $this->searchTicket($rq->term);
                break;

            case 'PunchList':
                $this->result = $this->searchPunchList($rq->term);
                break;

            case 'ChangeOrder':
                $this->result = $this->searchChangeOrder($rq->term);
                break;

            case 'BackCharge':
                $this->result = $this->searchBackCharge($rq->term);
                break;

            default:
                # code...
                break;
        }

        return response()->json([
            "search_term" => $rq->term,
            "type" => $rq->type,
            "total_records" => 1,
            "result" => $this->result
        ]);
    }



    protected function searchUser($term)
    {
        $this->fields = [
            "name",
            "email",
            "phone_number",
            "address"
        ];

        $query = User::query();

        return $this->handleSearch($query, $term);
    }



    protected function searchEmployee($term)
    {
        $this->fields = [
            "employment_type",
            "notes"
        ];

        $query = Employee::query();

        return $this->handleSearch($query, $term);
    }



    protected function searchClient($term)
    {
        $this->fields = [
            "company_name",
            "phone_gsm",
            "phone_stable",
        ];

        $query = Client::query();

        return $this->handleSearch($query, $term);
    }



    protected function searchTender($term)
    {
        $this->fields = [
            "project_name",
            "closing_date",
            "status",
            "notes",
        ];

        $query = Tender::query();

        return $this->handleSearch($query, $term);
    }



    protected function searchProject($term)
    {
        $this->fields = [
            "project_name",
            "start_date",
            "end_date",
            "status",
            "notes",
        ];

        $query = Project::query();

        return $this->handleSearch($query, $term);
    }



    protected function searchTask($term)
    {
        $this->fields = [
            "title",
            "start_date",
            "end_date",
            "status",
            "notes",
            "priority",
        ];

        $query = Task::query();

        return $this->handleSearch($query, $term);
    }



    protected function searchTicket($term)
    {
        $this->fields = [
            "subject",
            "status",
            "description"
        ];

        $query = Ticket::query();

        return $this->handleSearch($query, $term);
    }



    protected function searchPunchList($term)
    {
        $this->fields = [
            "end_date",
            "address",
            "priority",
            "status",
            "notes",
        ];

        $query = PunchList::query();

        return $this->handleSearch($query, $term);
    }



    protected function searchChangeOrder($term)
    {
        $this->fields = [
            "date",
            "floor",
            "unit_number",
            "description_of_change",
            "description_of_change_approval",
            "reason_for_change",
            "reason_for_change_approval",
            "additional_notes",
            "fix_amount",
            "charge_amount",
        ];

        $query = ChangeOrder::query();

        return $this->handleSearch($query, $term);
    }



    protected function searchBackCharge($term)
    {
        $this->fields = [
            "date",
            "floor",
            "unit_number",
            "description_of_change",
            "description_of_change_approval",
            "reason_for_change",
            "reason_for_change_approval",
            "additional_notes",
            "fix_amount",
            "charge_amount",
        ];

        $query = BackCharge::query();

        return $this->handleSearch($query, $term);
    }



    protected function handleSearch($query, $term)
    {
        if (!$this->fields)
            return $query->get();

        return $query->where(function ($query) use ($term) {
            foreach ($this->fields as $field) {
                if (str_contains($field, '.')) {
                    [$relationship, $relatedField] = explode('.', $field);

                    $query->orWhereHas($relationship, function ($query) use ($relatedField, $term) {
                        $query->where($relatedField, 'LIKE', "%{$term}%");
                    });
                } else
                    $query->orWhere($field, 'LIKE', "%{$term}%");
            }
        })
            ->get();
    }
}

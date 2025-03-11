<?php

namespace App\Services;

class DatatableService
{

    public function create($queryBuilder, $resource)
    {
        $numberOfRows = request()->input("perPage", 10);
        $search = request()->input("search", null);
        $searchableFields = request()->input("searchables", []);
        $sortables = request()->input("sort", ["col" => "created_at", "dir" => "desc"]);
        $archive = request()->input("archive", false);

        // return getType($archive);
        $archive = $archive === "true" ? true : false;

        $enableArchive = $this->checkIsArchiveable();

        $queryBuilder->when($search, function ($query) use ($search, $searchableFields) {

            $query->where(function ($query) use ($search, $searchableFields) {
                foreach ($searchableFields as $field) {
                    if (str_contains($field, '.')) {
                        [$relationship, $relatedField] = explode('.', $field);

                        $query->orWhereHas($relationship, function ($query) use ($relatedField, $search) {
                            $query->where($relatedField, 'LIKE', "%{$search}%");
                        });
                    } else
                        $query->orWhere($field, 'LIKE', "%{$search}%");
                }
            });
        });


        $queryBuilder->when(($enableArchive), function ($query) use ($archive) {
            if ($archive == true) {
                $query->where("archive", true);
            } else {
                $query->where("archive", false);
            }
        });


        if (!empty($sortables['col'])) {
            $sortable = $sortables['col'];
            $direction = strtolower($sortables['dir']) == 'desc' ? 'desc' : 'asc';
            $queryBuilder->orderBy($sortable, $direction);
            // return $queryBuilder->get();
            // dd($sortables);
        }

        return $resource::collection($queryBuilder->paginate($numberOfRows));
    }


    protected function checkIsArchiveable()
    {
        
        return in_array(request()->segments()[1], [
            "tenders",
            "projects",
            "tasks",
            "sales",
            "punch_list",
            "logistics",
            "installations",
            "change_order",
            "back_charge"
        ]);
    }
}

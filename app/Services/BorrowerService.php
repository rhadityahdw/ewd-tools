<?php

namespace App\Services;

use App\Models\Borrower;

class BorrowerService
{
    public function createBorrower(array $data): Borrower
    {
        $borrower = Borrower::create($data);

        return $borrower;
    }

    public function updateBorrower(Borrower $borrower, array $data): Borrower
    {
        $borrower->update($data);

        return $borrower;
    }
}
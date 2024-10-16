<?php

namespace App\Services\Tutorat;

use Carbon\Carbon;
use PhpParser\Node\Expr\Cast\Double;

class TutoratStoreDTO
{
    /**
     * @var int|null
     */
    public ?int $tutorat_id;

    /**
     * @var int
     */
    public int $class_level_id;

    /**
     * @var int
     */
    public int $subject_id;

    /**
     * @var int|null
     */
    public ?int $prof_id;

    /**
     * @var int|null
     */
    public ?int $etudiant_id;

    /**
     * @var string|null
     */
    public ?string $description;

    /**
     * @var string|null
     */
    public ?string $documents;

    /**
     * @var string|null
     */
    public ?string $subject;

    /**
     * @var Carbon
     */
    public Carbon $date;

    /**
     * @var Carbon
     */
    public Carbon $time_start;

    /**
     * @var Carbon
     */
    public Carbon $time_end;

    /**
     * @var float
     */
    public float $price;

    /**
     * @param int|null $tutorat_id
     * @param int $class_level_id
     * @param int $subject_id
     * @param int|null $prof_id
     * @param int|null $etudiant_id
     * @param string|null $description
     * @param string|null $documents
     * @param string|null $subject
     * @param Carbon $date
     * @param Carbon $time_start
     * @param Carbon $time_end
     * @param float $price
     */
    public function __construct(
        ?int $tutorat_id,
        int $class_level_id,
        int $subject_id,
        ?int $prof_id,
        ?int $etudiant_id,
        ?string $description,
        ?string $documents,
        ?string $subject,
        Carbon $date,
        Carbon $time_start,
        Carbon $time_end,
        float $price
    ) {
        $this->tutorat_id = $tutorat_id;
        $this->class_level_id = $class_level_id;
        $this->subject_id = $subject_id;
        $this->prof_id = $prof_id;
        $this->etudiant_id = $etudiant_id;
        $this->description = $description;
        $this->documents = $documents;
        $this->subject = $subject;
        $this->date = $date;
        $this->time_start = $time_start;
        $this->time_end = $time_end;
        $this->price = $price;
    }
}

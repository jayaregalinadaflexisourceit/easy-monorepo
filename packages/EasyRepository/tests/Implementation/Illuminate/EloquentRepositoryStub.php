<?php

declare(strict_types=1);

namespace EonX\EasyRepository\Tests\Implementation\Illuminate;

use EonX\EasyRepository\Implementations\Illuminate\AbstractEloquentRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

final class EloquentRepositoryStub extends AbstractEloquentRepository
{
    /**
     * @var null|callable
     */
    private $modelExpectations;

    public function __construct(?callable $modelExpectations = null)
    {
        $this->modelExpectations = $modelExpectations;

        parent::__construct();
    }

    protected function getModel(): Model
    {
        $mock = \Mockery::mock(Model::class);
        $collection = \Mockery::mock(Collection::class);

        if ($this->modelExpectations !== null) {
            \call_user_func($this->modelExpectations, $mock, $collection);
        }

        return $mock;
    }
}

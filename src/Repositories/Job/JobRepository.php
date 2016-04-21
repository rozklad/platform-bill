<?php namespace Sanatorium\Bill\Repositories\Job;

use Cartalyst\Support\Traits;
use Illuminate\Container\Container;
use Symfony\Component\Finder\Finder;

class JobRepository implements JobRepositoryInterface {

	use Traits\ContainerTrait, Traits\EventTrait, Traits\RepositoryTrait, Traits\ValidatorTrait;

	/**
	 * The Data handler.
	 *
	 * @var \Sanatorium\Bill\Handlers\Job\JobDataHandlerInterface
	 */
	protected $data;

	/**
	 * The Eloquent bill model.
	 *
	 * @var string
	 */
	protected $model;

	/**
	 * Constructor.
	 *
	 * @param  \Illuminate\Container\Container  $app
	 * @return void
	 */
	public function __construct(Container $app)
	{
		$this->setContainer($app);

		$this->setDispatcher($app['events']);

		$this->data = $app['sanatorium.bill.job.handler.data'];

		$this->setValidator($app['sanatorium.bill.job.validator']);

		$this->setModel(get_class($app['Sanatorium\Bill\Models\Job']));
	}

	/**
	 * {@inheritDoc}
	 */
	public function grid()
	{
		return $this
			->createModel();
	}

	/**
	 * {@inheritDoc}
	 */
	public function findAll()
	{
		return $this->container['cache']->rememberForever('sanatorium.bill.job.all', function()
		{
			return $this->createModel()->get();
		});
	}

	/**
	 * {@inheritDoc}
	 */
	public function find($id)
	{
		return $this->container['cache']->rememberForever('sanatorium.bill.job.'.$id, function() use ($id)
		{
			return $this->createModel()->find($id);
		});
	}

	/**
	 * {@inheritDoc}
	 */
	public function validForCreation(array $input)
	{
		return $this->validator->on('create')->validate($input);
	}

	/**
	 * {@inheritDoc}
	 */
	public function validForUpdate($id, array $input)
	{
		return $this->validator->on('update')->validate($input);
	}

	/**
	 * {@inheritDoc}
	 */
	public function store($id, array $input)
	{
		return ! $id ? $this->create($input) : $this->update($id, $input);
	}

	/**
	 * {@inheritDoc}
	 */
	public function create(array $input)
	{
		// Create a new job
		$job = $this->createModel();

		// Fire the 'sanatorium.bill.job.creating' event
		if ($this->fireEvent('sanatorium.bill.job.creating', [ $input ]) === false)
		{
			return false;
		}

		// Prepare the submitted data
		$data = $this->data->prepare($input);

		// Validate the submitted data
		$messages = $this->validForCreation($data);

		// Check if the validation returned any errors
		if ($messages->isEmpty())
		{
			// Save the job
			$job->fill($data)->save();

			// Fire the 'sanatorium.bill.job.created' event
			$this->fireEvent('sanatorium.bill.job.created', [ $job ]);
		}

		return [ $messages, $job ];
	}

	/**
	 * {@inheritDoc}
	 */
	public function update($id, array $input)
	{
		// Get the job object
		$job = $this->find($id);

		// Fire the 'sanatorium.bill.job.updating' event
		if ($this->fireEvent('sanatorium.bill.job.updating', [ $job, $input ]) === false)
		{
			return false;
		}

		// Prepare the submitted data
		$data = $this->data->prepare($input);

		// Validate the submitted data
		$messages = $this->validForUpdate($job, $data);

		// Check if the validation returned any errors
		if ($messages->isEmpty())
		{
			// Update the job
			$job->fill($data)->save();

			// Fire the 'sanatorium.bill.job.updated' event
			$this->fireEvent('sanatorium.bill.job.updated', [ $job ]);
		}

		return [ $messages, $job ];
	}

	/**
	 * {@inheritDoc}
	 */
	public function delete($id)
	{
		// Check if the job exists
		if ($job = $this->find($id))
		{
			// Fire the 'sanatorium.bill.job.deleted' event
			$this->fireEvent('sanatorium.bill.job.deleted', [ $job ]);

			// Delete the job entry
			$job->delete();

			return true;
		}

		return false;
	}

	/**
	 * {@inheritDoc}
	 */
	public function enable($id)
	{
		$this->validator->bypass();

		return $this->update($id, [ 'enabled' => true ]);
	}

	/**
	 * {@inheritDoc}
	 */
	public function disable($id)
	{
		$this->validator->bypass();

		return $this->update($id, [ 'enabled' => false ]);
	}

}

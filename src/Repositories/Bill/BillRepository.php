<?php namespace Sanatorium\Bill\Repositories\Bill;

use Cartalyst\Support\Traits;
use Illuminate\Container\Container;
use Symfony\Component\Finder\Finder;

class BillRepository implements BillRepositoryInterface {

	use Traits\ContainerTrait, Traits\EventTrait, Traits\RepositoryTrait, Traits\ValidatorTrait;

	/**
	 * The Data handler.
	 *
	 * @var \Sanatorium\Bill\Handlers\Bill\BillDataHandlerInterface
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

		$this->data = $app['sanatorium.bill.bill.handler.data'];

		$this->setValidator($app['sanatorium.bill.bill.validator']);

		$this->setModel(get_class($app['Sanatorium\Bill\Models\Bill']));
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
		return $this->container['cache']->rememberForever('sanatorium.bill.bill.all', function()
		{
			return $this->createModel()->get();
		});
	}

	/**
	 * {@inheritDoc}
	 */
	public function find($id)
	{
		return $this->container['cache']->rememberForever('sanatorium.bill.bill.'.$id, function() use ($id)
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
		// Create a new bill
		$bill = $this->createModel();

		// Fire the 'sanatorium.bill.bill.creating' event
		if ($this->fireEvent('sanatorium.bill.bill.creating', [ $input ]) === false)
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
			// Save the bill
			$bill->fill($data)->save();

			// Fire the 'sanatorium.bill.bill.created' event
			$this->fireEvent('sanatorium.bill.bill.created', [ $bill ]);
		}

		return [ $messages, $bill ];
	}

	/**
	 * {@inheritDoc}
	 */
	public function update($id, array $input)
	{
		// Get the bill object
		$bill = $this->find($id);

		// Fire the 'sanatorium.bill.bill.updating' event
		if ($this->fireEvent('sanatorium.bill.bill.updating', [ $bill, $input ]) === false)
		{
			return false;
		}

		// Prepare the submitted data
		$data = $this->data->prepare($input);

		// Validate the submitted data
		$messages = $this->validForUpdate($bill, $data);

		// Check if the validation returned any errors
		if ($messages->isEmpty())
		{
			// Update the bill
			$bill->fill($data)->save();

			// Fire the 'sanatorium.bill.bill.updated' event
			$this->fireEvent('sanatorium.bill.bill.updated', [ $bill ]);
		}

		return [ $messages, $bill ];
	}

	/**
	 * {@inheritDoc}
	 */
	public function delete($id)
	{
		// Check if the bill exists
		if ($bill = $this->find($id))
		{
			// Fire the 'sanatorium.bill.bill.deleted' event
			$this->fireEvent('sanatorium.bill.bill.deleted', [ $bill ]);

			// Delete the bill entry
			$bill->delete();

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

<?php
namespace Mett;
/**
 * Class FiniteStateMachine
 */
class FiniteStateMachine
{
    protected $_states;
    protected $_indexes;
    protected $_currentState;

    /**
     * Initializes the simple finite state machine.
     *
     * @param $states array of states
     *
     * <code>
     *  $state configuration example:
     *
     *    $states = array(
     *        array(
     *            'state'      => 'working',
     *            'initial'    => true,
     *            'transition' => array(
     *                'withdrawal'       => 'smoking',
     *                'call_for_meeting' => 'meeting'),
     *            'action'     => function() {
     *                echo "I'm working.\n";
     *            }),
     *        array(
     *            'state'      => 'smoking',
     *            'transition' => array(
     *                'smoked finished'  => 'working',
     *                'call_for_meeting' => 'hiding'),
     *            'action'     => function() {
     *                echo "I'm thinking.\n";
     *            }),
     *        array(
     *            'state'      => 'meeting',
     *            'transition' => array(
     *                'meetings_over'    => 'working'),
     *            'action'     => function() {
     *                echo "I'm in a meeting.\n";
     *            }),
     *        array(
     *            'state'      => 'hiding',
     *            'transition' => array(
     *                'meetings_over'    => 'working',
     *                'smoked finished'  => 'working'),
     *            'action'     => function() {
     *                echo "I smoke until done.\n";
     *        })
     *    );
     *
     *    use Mett;
     *    $fsm = new FiniteStateMachine($state);
     *    $fsm->emit
     *
     * </code>
     *
     *
     */
    public function __construct($states)
    {
        $this->_states = $states;
        $this->indexes = array();

        if (empty($states)) {
            throw new FiniteStateMachine\InvalidStatesException('Finite state machine must have at least one configured state.');
        }

        foreach ($this->_states as $stateNumber => $state) {
            $this->_indexes[$state['state']] = $stateNumber;
            if (isset($state['initial'])) {
                $this->_initializeInitState($state['state']);
            }
        }

        if (empty($this->_currentState)) {
            throw new FiniteStateMachine\NoInitialStateException('No initial state given in transition table.');
        }
    }

    protected function _initializeInitState($state)
    {
        if (empty($this->_currentState) && isset($this->_indexes[$state])) {
            $this->_currentState = $this->_states[$this->_indexes[$state]];
            $this->execAction();
       }
    }

    public function dispatch($transition)
    {
        if (isset($this->_currentState['transition'][$transition])) {
            $this->_currentState = $this->_states[$this->_indexes[$this->_currentState['transition'][$transition]]];
            $this->execAction();
        }
    }

    public function execAction(array $params = null)
    {
        if (is_callable($this->_currentState['action'])) {

            call_user_func($this->_currentState['action'], $params);
        }
    }

    public function getState()
    {
        return $this->_currentState['state'];
    }
}
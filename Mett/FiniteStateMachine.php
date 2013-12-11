<?php
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

        foreach ($this->_states as $stateNumber => $state) {
            $this->_indexes[$state['state']] = $stateNumber;
            if (isset($state['initial'])) {
                $this->_currentState = $state;
                $this->execAction();
            }
        }

        if (null == $this->_currentState) {
            throw new \Exception('No initial state given in transition table.');
        }
    }

    public function dispatch($transition)
    {
        if (empty($this->_currentState) && isset($this->_indexes[$transition])) {
            $this->_currentState = $this->_states[$this->_indexes[$transition]];

            return;
        }

        if (isset($this->_currentState['transition'][$transition])) {
            $this->_currentState = $this->_states[$this->_indexes[$this->_currentState['transition'][$transition]]];
            $this->execAction();
        }
    }

    public function execAction()
    {
        if (is_callable($this->_currentState['action'])) {

            return call_user_func($this->_currentState['action']);
        }

        return null;
    }

    public function getState()
    {
        if (empty($this->_currentState)) {

            return null;
        }

        return $this->_currentState['state'];
    }
}
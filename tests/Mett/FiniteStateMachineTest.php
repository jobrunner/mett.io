<?php

use Mett\FiniteStateMachine;

class MettFiniteStateMachineTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->states = array(
            array(
                'state'      => 'working',
                'initial'    => true,
                'transition' => array(
                    'withdrawal'       => 'smoking',
                    'call_for_meeting' => 'meeting'),
                'action'     => function() {
                    echo "I'm working.\n";
                }),
            array(
                'state'      => 'smoking',
                'transition' => array(
                    'smoked finished'  => 'working',
                    'call_for_meeting' => 'hiding'),
                'action'     => function() {
                    echo "I'm thinking.\n";
                }),
            array(
                'state'      => 'meeting',
                'transition' => array(
                    'meetings_over'    => 'working'),
                'action'     => function() {
                    echo "I'm in a meeting.\n";
                }),
            array(
                'state'      => 'hiding',
                'transition' => array(
                    'meetings_over'    => 'working',
                    'smoked finished'  => 'working'),
                'action'     => function() {
                    echo "I smoke until done.\n";
                })
        );
    }

    public function testFiniteStateMachine()
    {
        $this->expectOutputString("I'm working.\nI'm thinking.\nI smoke until done.\nI'm working.\n");

        // goes into initial state and implicitly executes action
        $fsm = new FiniteStateMachine($this->states);

        // testing the initial state
        $this->assertEquals("working", $fsm->getState());

        // testing a transition that will be ignored because state is already set.
        $fsm->dispatch('withdrawal');
        $this->assertEquals("smoking", $fsm->getState());

        // fire a transition "withdrawal" in working state:
        $fsm->dispatch('withdrawal');
        $this->assertEquals("smoking", $fsm->getState());

        // someone tries to call us at smoking, but I will hide myself until smoking done:
        $fsm->dispatch('call_for_meeting');
        $this->assertEquals("hiding", $fsm->getState());

        // ok, I'm finished smoking and have to work
        $fsm->dispatch('smoked finished');
        $this->assertEquals("working", $fsm->getState());
    }

    public function testFiniteStateMachineAction()
    {
        $this->expectOutputString("I'm working.\nI'm working.\n");

        // goes into initial state and implicitly executes action
        $fsm = new FiniteStateMachine($this->states);

        // explicitly executes action of initial state
        $fsm->execAction();
    }

    /**
     * @expectedException \Mett\FiniteStateMachine\InvalidStatesException
     */
    public function testFiniteStateMachineStateDefinition()
    {
        // must throw an exception because FSM cannot got into any state
        $fsm = new FiniteStateMachine(null);
    }

    /**
     * @expectedException \Mett\FiniteStateMachine\NoInitialStateException
     */
    public function testFiniteStateMachineStateDefinition2()
    {
        // must throw an exception because FSM cannot got into any state
        $states = $this->states;
        unset($states[0]['initial']);

        $fsm = new FiniteStateMachine($states);
    }
}
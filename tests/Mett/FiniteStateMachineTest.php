<?php
class MettFiniteStateMachineTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->uuidFormat = "#^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$#";
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

    public function testMettFiniteStateMachine()
    {
        $this->expectOutputString("I'm working.\nI'm thinking.\nI smoke until done.\nI'm working.\n");
        $fsm = new Mett\FiniteStateMachine($this->states);

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
}

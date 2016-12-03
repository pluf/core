<?php

/**
 * State machine system.
 * 
 * 
 */
class Pluf_StateMachine
{

    const KEY_ACTION = 'action';
    
    const STATE_OTHERS = '*';

    const STATE_UNDEFINED = '#';

    var $states = null;
    
    // XXX: maso, handle otherwise states
    // XXX: maso, handle undefined state
    public function transact ($request, $object, $action)
    {
        $stateName = $object->state;
        if(empty($stateName))
        {
            $stateName = Pluf_StateMachine::STATE_UNDEFINED;
            $state = null;
            if(array_key_exists(Pluf_StateMachine::STATE_UNDEFINED, $this->states)){
                $transaction =  $this->states[Pluf_StateMachine::STATE_UNDEFINED];
            } else {
                throw new Pluf_Exception(
                        sprintf("Unknown state!", $stateName));
            }
        } else {
            $state = $this->getState($object);
            $state['name'] = $object->state;
            $transaction = $this->getTransaction($state, $action);
        }
        $this->checkPreconditions($request, $object, $action, $transaction);
        // Run the transaction
        if(array_key_exists(Pluf_StateMachine::KEY_ACTION, $transaction)){
            call_user_func_array($transaction[Pluf_StateMachine::KEY_ACTION], 
                    array(
                            $request,
                            $object,
                            $action
                    ));
        }
        // Update state
        $object->state = $transaction['next'];
        $object->update();
        
        // Send signals
        $event = new Pluf_StateMachine_Event(
                $request, 
                $object, 
                $action, 
                $state, 
                $transaction);
        Pluf_Signal::send('DigiDoci_Request::stateChange', 'Pluf_StateMachine', 
                $event);
        return $this;
    }

    /*
     * Gets state
     */
    private function getState ($object)
    {
        $stateName = $object->state;
        // check state
        if (! array_key_exists($stateName, $this->states)) {
            // throw invalid state
            throw new Pluf_Exception(
                    sprintf("State not found(name:%s)", $stateName));
        }
        return $this->states[$stateName];
    }

    private function getTransaction ($state, $action)
    {
        // check action
        if (! array_key_exists($action, $state)) {
            // throw invalid transaction
            throw new Pluf_Exception(
                    sprintf("transaction not found (State:%s, Action:%s)", 
                            $state['name'], $action));
        }
        return $state[$action];
    }

    private function checkPreconditions ($request, $object, $action, $transaction)
    {
        // check all preconditions
        $preconds = array();
        if (array_key_exists('preconditions', $transaction)) {
            $precond = $transaction['preconditions'];
        }
        foreach ($preconds as $precond) {
            call_user_func_array(explode('::', $precond), 
                    array(
                            $request,
                            $object,
                            $action
                    ));
        }
    }

    public function setStates ($states)
    {
        $this->states = $states;
        return $this;
    }

    public function setSignals ($signals)
    {
        $this->signals = $signals;
        return $this;
    }

    public function setInitialState ($initialState)
    {
        $this->initialState = $initialState;
        return $this;
    }

    public function setProperty ($statePropertyName)
    {
        $this->statePropertyName = $statePropertyName;
        return $this;
    }
}


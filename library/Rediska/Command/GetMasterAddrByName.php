<?php

/**
 * Get ip address and port of current master from sentinel
 *
 */
class Rediska_Command_GetMasterAddrByName extends Rediska_Command_Abstract
{
    protected $_connections = array();

    /**
     * Create
     *
     * @param $name
     * @return array
     * @throws Rediska_Connection_Exception
     */
    public function create($name = 'mymaster')
    {
        $command = array('SENTINEL', 'get-master-addr-by-name', $name);
        $commands = array();
        foreach($this->_rediska->getConnections() as $connection) {
            $this->_connections[] = $connection->getAlias();
            $commands[] = new Rediska_Connection_Exec($connection, $command);
        }

        return $commands;
    }

    /**
     * Parse response
     *
     * @param array $response
     * @return array
     */
    public function parseResponse($response)
    {
        if (!empty($response) && count($response) === 2) {
            return array(
                'host' => $response[0],
                'port' => $response[1]
            );
        } else {
            return $this->getRediska()->getSerializer()->unserialize($response);
        }
    }
}

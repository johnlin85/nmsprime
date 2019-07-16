<?php

/*
|--------------------------------------------------------------------------
| Language lines for module ProvVoipEnvia
|--------------------------------------------------------------------------
|
| The following language lines are used by the module ProvVoipEnvia
| As far as we know this module is in use in Germany, only. So no translation
| for other languages is needed at the moment.
|
 */

return [
    'api' => [
        'clearEnviaContractReference' => 'Clear envia TEL contract reference (local)',
        'contract' => 'Telephone connection (= envia TEL contract)',
        'contractChangeTariff' => 'Change tariff',
        'contractChangeVariation' => 'Change purchase tariff',
        'contractCreate' => 'Create envia TEL contract',
        'contractGetReference' => 'Get envia TEL contract reference',
        'contractGetTariff' => 'Get envia TEL sales tariff',
        'contractGetVariation' => 'Get envia TEL purchase tariff',
        'contractGetVoiceData' => 'Get voice data',
        'contractRelocate' => 'Relocate contract',
        'customer' => 'Customer',
        'customerGetContracts' => 'Get envia TEL contracts',
        'customerGetReference' => 'Get envia TEL customer reference',
        'customerGetReferenceLegacy' => 'Get envia TEL customer reference (using legacy customer number)',
        'customerUpdate' => 'update customer at envia TEL',
        'customerUpdateNumber' => 'Update customer number at envia TEL',
        'misc' => 'Miscellaneous',
        'miscGetFreeNumbers' => 'Get free phonenumbers',
        'miscGetKeys' => 'Get key values for use in other methods',
        'miscGetUsageCsv' => 'Get user statistics of all users',
        'miscPing' => 'Test envia TEL API (ping)',
        'order' => 'Orders',
        'phonebookEntry' => 'Phonebook entry',
        'phonebookEntryCreate' => 'Create phonebook entry',
        'phonebookEntryDelete' => 'Delete phonebook entry',
        'phonebookEntryGet' => 'Get phonebook entry',
        'voipAccount' => 'Phonenumber',
        'voipAccountCreate' => 'Create VoIP account',
        'voipAccountTerminate' => 'Terminate VoIP account',
        'voipAccountUpdate' => 'Update VoIP account',
    ],
    'enviaContract' => [
        'contId' => 'envia TEL contract ID',
        'contract' => 'Contract (= envia TEL customer)',
        'contractId' => 'Contract ID',
        'custId' => 'envia TEL customer ID',
        'endDate' => 'End date',
        'lockLevel' => 'Lock level',
        'method' => 'Method',
        'modem' => 'Modem',
        'modemId' => 'Modem ID',
        'nextContract' => 'Next envia TEL contract ID',
        'orderId' => 'Order ID',
        'orderdate' => 'Orderdate',
        'orders' => 'envia TEL orders',
        'orderstatus' => 'Orderstatus',
        'ordertype' => 'Ordertype',
        'phonenumbers' => 'Phonenumbers',
        'prevContract' => 'Previous envia TEL contract ID',
        'proto' => 'Protocol',
        'sla' => 'SLA ID',
        'startDate' => 'Start date',
        'state' => 'State',
        'tariff' => 'Tariff ID',
        'variation' => 'Variation ID',
    ],
    'enviaOrder' => [
        'activationDate' => 'Activation date NMS',
        'activationDateEnvia' => 'Activation date envia TEL',
        'active' => 'Active',
        'address' => 'Address',
        'configfile' => 'Configfile',
        'contract' => 'Contract (= envia TEL customer)',
        'contractEnd' => 'Contract end',
        'contractId' => 'Contract ID',
        'contractReference' => 'envia TEL contract reference',
        'contractStart' => 'Contract start',
        'createdAt' => 'Created at',
        'customerReference' => 'envia TEL customer reference',
        'deactivationDate' => 'Deactivation date NMS',
        'deactivationDateEnvia' => 'Deactivation date envia TEL',
        'enviaTelContract' => 'envia TEL contract',
        'enviaTelContractId' => 'contract reference',
        'fix' => 'fixed',
        'hasInternet' => 'Internet',
        'hasTelephony' => 'Telephony',
        'hostname' => 'Hostname',
        'id' => 'Order ID',
        'installationAddress' => 'Installation address',
        'items' => 'Items (Internet and VoIP only)',
        'lastStatusUpdate' => 'Last status update',
        'lastUserInteraction' => 'Last user interaction',
        'listAll' => 'List of all envia TEL orders',
        'listInteractionNeeding' => 'List of envia TEL orders needing user interaction',
        'macAddress' => 'MAC address',
        'markAsSolved' => 'Mark as solved.',
        'method' => 'Method',
        'modem' => 'Modem (can hold multiple envia TEL contracts)',
        'modemId' => 'Modem ID',
        'number' => 'Number',
        'ordercomment' => 'Ordercomment',
        'orderdate' => 'Orderdate',
        'orderstatus' => 'Orderstatus',
        'orderstatusId' => 'Orderstatus ID',
        'ordertype' => 'Ordertype',
        'ordertypeId' => 'Ordertype ID',
        'phonenumber' => 'Phonenumber',
        'phonenumberId' => 'Phonenumber ID',
        'phonenumbers' => 'Phonenumbers',
        'product' => 'Product',
        'qos' => 'QoS',
        'relatedOrderCreated' => 'Related order created',
        'relatedOrderDeleted' => 'Related order deleted',
        'relatedOrderId' => 'Related order ID',
        'relatedOrderLastUpdated' => 'Related order last updated',
        'relatedOrderType' => 'Related order type',
        'showAll' => 'show all envia TEL orders',
        'showInteractionNeeding' => 'show only envia TEL orders needing user interaction',
        'state' => 'State',
        'type' => 'Type',
        'validFrom' => 'Start date',
        'validTo' => 'End date',
    ],
];

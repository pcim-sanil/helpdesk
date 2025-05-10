<?php

namespace App\Features\Ticket;

class TicketService
{

}

CREATE TABLE `tickets` (
    `ticket_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `enquiry_type` enum('0','1','2','3','4','5','6','7','8','9') DEFAULT NULL COMMENT '0-> Unsubscribe 19 SMS, 1-> Unsubscribe 04 Marketing , 2-> Incoming Call, 3 -> Recorded Msg, 4 -> Answered, 5 -> In Call, 6 -> Manual Ticket, 7 -> Email Ticket, 8 -> Email query, 9 -> Ext Redirect',
    `ip_address` varchar(100) DEFAULT NULL,
    `attachment` mediumtext,
    `recorded_message` varchar(255) DEFAULT NULL,
    `priority` enum('0','1','2','3') DEFAULT NULL COMMENT '0-> Normal , 3-> Highest Priority',
    `status` enum('0','1','2','3','4') DEFAULT NULL COMMENT '0-> Open, 1-> Close, 2-> Escalated, 3-> Pending, 4-> Escalated to vendor',
    `operator_id` int(11) unsigned NOT NULL,
    `caller_info_mobile` varchar(100) DEFAULT NULL COMMENT 'FK for caller info',
    `caller_info_mobile_8` varchar(10) DEFAULT NULL,
    `caller_email` varchar(255) DEFAULT NULL,
    `created_date` datetime DEFAULT NULL,
    `updated_date` datetime DEFAULT NULL,
    `enduser_short_code` varchar(20) DEFAULT NULL,
    `service_1300` varchar(20) DEFAULT NULL,
    `duration` int(11) DEFAULT '0',
    `marketing_04` varchar(20) DEFAULT NULL,
    `incoming_call_key` varchar(20) DEFAULT NULL,
    `callback_number` varchar(100) DEFAULT NULL,
    `notes` blob,
    `subcompany` int(11) DEFAULT NULL,
    `reassigned_from` int(11) DEFAULT NULL,
    `summary` int(11) DEFAULT NULL,
    `duplicateId` int(11) DEFAULT NULL,
    `is_third_party` tinyint(4) DEFAULT NULL,
    `created_by` int(10) unsigned DEFAULT NULL,
    `is_assigned` tinyint(1) DEFAULT NULL,
    `assigned_to` int(11) DEFAULT NULL,
    `is_call_on` tinyint(1) DEFAULT NULL,
    `is_urgent` tinyint(4) NOT NULL DEFAULT '0',
    `is_voicefile_sent` int(11) DEFAULT '0',
    `internal_note` blob NOT NULL,
    `enc_flag` tinyint(4) DEFAULT '1',
    `source_auth` varchar(25) DEFAULT '172.31.50.143' COMMENT 'for IVR Handshake',
    `cbd_call_ref_id` int(11) DEFAULT '1' COMMENT 'Open ticket id',
    `cbd_op_number` int(11) DEFAULT '1' COMMENT 'Agent Number',
    `country_name_based_on_api` varchar(255) DEFAULT NULL,
    `stop` tinyint(4) DEFAULT NULL,
    `reason_for_contact` varchar(255) DEFAULT NULL,
    `category` varchar(255) DEFAULT NULL,
    PRIMARY KEY (`ticket_id`)
  ) ENGINE=InnoDB AUTO_INCREMENT=3028470 DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC
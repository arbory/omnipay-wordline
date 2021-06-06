<?php

namespace Omnipay\FirstDataLatvia\Helpers;

class ResultCodeMessages
{
    static protected $codes = [
         0     => [ "action" => "Approved", "description" => "Approved"],
         1     => [ "action" => "Approved with ID", "description" => "Approved, honour with identification"],
         2     => [ "action" => "Approved", "description" => "Approved for partial amount"],
         3     => [ "action" => "Approved", "description" => "Approved for VIP"],
         4     => [ "action" => "Approved", "description" => "Approved, update track 3"],
         5     => [ "action" => "Approved", "description" => "Approved, account type specified by card issuer"],
         6     => [ "action" => "Approved", "description" => "Approved for partial amount, account type specified by card issuer"],
         7     => [ "action" => "Approved", "description" => "Approved, update ICC"],
         100  => [ "action" => "Declined", "description" => "Decline (general, no comments)"],
         101  => [ "action" => "Declined", "description" => "Decline, expired card"],
         102  => [ "action" => "Declined", "description" => "Decline, suspected fraud"],
         103  => [ "action" => "Declined", "description" => "Decline, card acceptor contact acquirer"],
         104  => [ "action" => "Declined", "description" => "Decline, restricted card"],
         105  => [ "action" => "Declined", "description" => "Decline, card acceptor call acquirer's security department"],
         106  => [ "action" => "Declined", "description" => "Decline, allowable PIN tries exceeded"],
         107  => [ "action" => "Declined", "description" => "Decline, refer to card issuer"],
         108  => [ "action" => "Declined", "description" => "Decline, refer to card issuer's special conditions"],
         109  => [ "action" => "Declined", "description" => "Decline, invalid merchant"],
         110  => [ "action" => "Declined", "description" => "Decline, invalid amount"],
         111  => [ "action" => "Declined", "description" => "Decline, invalid card number"],
         112  => [ "action" => "Declined", "description" => "Decline, PIN data required"],
         113  => [ "action" => "Declined", "description" => "Decline, unacceptable fee"],
         114  => [ "action" => "Declined", "description" => "Decline, no account of type requested"],
         115  => [ "action" => "Declined", "description" => "Decline, requested function not supported"],
         116  => [ "action" => "Declined", "description" => "Decline, not sufficient funds"],
         117  => [ "action" => "Declined", "description" => "Decline, incorrect PIN"],
         118  => [ "action" => "Declined", "description" => "Decline, no card record"],
         119  => [ "action" => "Declined", "description" => "Decline, transaction not permitted to cardholder"],
         120  => [ "action" => "Declined", "description" => "Decline, transaction not permitted to terminal"],
         121  => [ "action" => "Declined", "description" => "Decline, exceeds withdrawal amount limit"],
         122  => [ "action" => "Declined", "description" => "Decline, security violation"],
         123  => [ "action" => "Declined", "description" => "Decline, exceeds withdrawal frequency limit"],
         124  => [ "action" => "Declined", "description" => "Decline, violation of law"],
         125  => [ "action" => "Declined", "description" => "Decline, card not effective"],
         126  => [ "action" => "Declined", "description" => "Decline, invalid PIN block"],
         127  => [ "action" => "Declined", "description" => "Decline, PIN length error"],
         128  => [ "action" => "Declined", "description" => "Decline, PIN kay synch error"],
         129  => [ "action" => "Declined", "description" => "Decline, suspected counterfeit card"],
         198  => [ "action" => "Declined", "description" => "Decline, call Card Processing Centre"],
         197  => [ "action" => "Declined", "description" => "Decline, call AmEx"],
         202  => [ "action" => "Pick-up", "description" => "Pick-up, suspected fraud"],
         203  => [ "action" => "Pick-up", "description" => "Pick-up, card acceptor contact card acquirer"],
         204  => [ "action" => "Pick-up", "description" => "Pick-up, restricted card"],
         205  => [ "action" => "Pick-up", "description" => "Pick-up, card acceptor call acquirer's security department"],
         206  => [ "action" => "Pick-up", "description" => "Pick-up, allowable PIN tries exceeded"],
         207  => [ "action" => "Pick-up", "description" => "Pick-up, special conditions"],
         208  => [ "action" => "Pick-up", "description" => "Pick-up, lost card"],
         209  => [ "action" => "Pick-up", "description" => "Pick-up, stolen card"],
         210  => [ "action" => "Pick-up", "description" => "Pick-up, suspected counterfeit card"],
         300  => [ "action" => "Call acquirer", "description" => "Status message: file action successful"],
         301  => [ "action" => "Call acquirer", "description" => "Status message: file action not supported by receiver"],
         302  => [ "action" => "Call acquirer", "description" => "Status message: unable to locate record on file"],
         303  => [ "action" => "Call acquirer", "description" => "Status message: duplicate record, old record replaced"],
         304  => [ "action" => "Call acquirer", "description" => "Status message: file record field edit error"],
         305  => [ "action" => "Call acquirer", "description" => "Status message: file locked out"],
         306  => [ "action" => "Call acquirer", "description" => "Status message: file action not successful"],
         307  => [ "action" => "Call acquirer", "description" => "Status message: file data format error"],
         308  => [ "action" => "Call acquirer", "description" => "Status message: duplicate record, new record rejected"],
         309  => [ "action" => "Call acquirer", "description" => "Status message: unknown file"],
         400  => [ "action" => "Accepted", "description" => "Accepted (for reversal)"],
         500  => [ "action" => "Call acquirer", "description" => "Status message: reconciled, in balance"],
         501  => [ "action" => "Call acquirer", "description" => "Status message: reconciled, out of balance"],
         502  => [ "action" => "Call acquirer", "description" => "Status message: amount not reconciled, totals provided"],
         503  => [ "action" => "Call acquirer", "description" => "Status message: totals for reconciliation not available"],
         504  => [ "action" => "Call acquirer", "description" => "Status message: not reconciled, totals provided"],
         600  => [ "action" => "Accepted", "description" => "Accepted (for administrative info)"],
         601  => [ "action" => "Call acquirer", "description" => "Status message: impossible to trace back original transaction"],
         602  => [ "action" => "Call acquirer", "description" => "Status message: invalid transaction reference number"],
         603  => [ "action" => "Call acquirer", "description" => "Status message: reference number/PAN incompatible"],
         604  => [ "action" => "Call acquirer", "description" => "Status message: POS photograph is not available"],
         605  => [ "action" => "Call acquirer", "description" => "Status message: requested item supplied"],
         606  => [ "action" => "Call acquirer", "description" => "Status message: request cannot be fulfilled - required documentation is not available"],
         700  => [ "action" => "Accepted", "description" => "Accepted (for fee collection)"],
         800  => [ "action" => "Accepted", "description" => "Accepted (for network management)"],
         900  => [ "action" => "Accepted", "description" => "Advice acknowledged, no financial liability accepted"],
         901  => [ "action" => "Accepted", "description" => "Advice acknowledged, finansial liability accepted"],
         902  => [ "action" => "Call acquirer", "description" => "Decline reason message: invalid transaction"],
         903  => [ "action" => "Call acquirer", "description" => "Status message: re-enter transaction"],
         904  => [ "action" => "Call acquirer", "description" => "Decline reason message: format error"],
         905  => [ "action" => "Call acquirer", "description" => "Decline reason message: acqiurer not supported by switch"],
         906  => [ "action" => "Call acquirer", "description" => "Decline reason message: cutover in process"],
         907  => [ "action" => "Call acquirer", "description" => "Decline reason message: card issuer or switch inoperative"],
         908  => [ "action" => "Call acquirer", "description" => "Decline reason message: transaction destination cannot be found for routing"],
         909  => [ "action" => "Call acquirer", "description" => "Decline reason message: system malfunction"],
         910  => [ "action" => "Call acquirer", "description" => "Decline reason message: card issuer signed off"],
         911  => [ "action" => "Call acquirer", "description" => "Decline reason message: card issuer timed out"],
         912  => [ "action" => "Call acquirer", "description" => "Decline reason message: card issuer unavailable"],
         913  => [ "action" => "Call acquirer", "description" => "Decline reason message: duplicate transmission"],
         914  => [ "action" => "Call acquirer", "description" => "Decline reason message: not able to trace back to original transaction"],
         915  => [ "action" => "Call acquirer", "description" => "Decline reason message: reconciliation cutover or checkpoint error"],
         916  => [ "action" => "Call acquirer", "description" => "Decline reason message: MAC incorrect"],
         917  => [ "action" => "Call acquirer", "description" => "Decline reason message: MAC key sync error"],
         918  => [ "action" => "Call acquirer", "description" => "Decline reason message: no communication keys available for use"],
         919  => [ "action" => "Call acquirer", "description" => "Decline reason message: encryption key sync error"],
         920  => [ "action" => "Call acquirer", "description" => "Decline reason message: security software/hardware error - try again"],
         921  => [ "action" => "Call acquirer", "description" => "Decline reason message: security software/hardware error - no action"],
         922  => [ "action" => "Call acquirer", "description" => "Decline reason message: message number out of sequence"],
         923  => [ "action" => "Call acquirer", "description" => "Status message: request in progress"],
         940  => [ "action" => "Not accepted", "description" => "Decline, blocked by fraud filter"],
         950  => [ "action" => "Not accepted", "description" => "Decline reason message: violation of business arrangement"]
    ];

    static function getAction(int $code)
    {
        if(isset(self::$codes[$code]) && isset(self::$codes[$code]['action'])){
            return self::$codes[$code]['action'];
        }
        return null;
    }

    static function getDescription(int $code)
    {
        if(isset(self::$codes[$code]) && isset(self::$codes[$code]['description'])){
            return self::$codes[$code]['description'];
        }
        return null;
    }
}
[{include file="email/html/header.tpl"}]
<p>Sehr geehrte(r) [{$sRecipientName}],</p>
<p>Die Bestellung vom [{$sOrderDate}] mit der Nummer [{$sOrderNumber}] hat soeben Ihren Status geändert.</p>
<p>Transaktionsnummer: [{$sDON}]</p>
<p>Neuer Status: [{$sNewState}]</p>
[{include file="email/html/footer.tpl"}]
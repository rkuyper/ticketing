Chibicon 2010 registratiesysteem: Registratiestatus
Beste {$name},

Je hebt op onze website gevraagd om een e-mail met daarin de status van jouw registraties. Onder dit e-mailadres zijn bij ons de volgende registraties bekend:

{section name='tickets' loop=$extra}
-Registratienummer {$extra[tickets].ticketnumber}: {if $extra[tickets].state == 'Betaald'}Betaald, kaartje(s): http://www.chibicon.nl/ticket.php?n={$extra[tickets].ticketnumber}&h={$extra[tickets].hash}{elseif $extra[tickets].state == 'Inactief'}Inactief{elseif $extra[tickets].state == 'Onbevestigd' || $extra[tickets].state == 'Con-ticket'}Onbevestigd{elseif $extra[tickets].state == 'Bevestigd' || $extra[tickets].state == 'Gewaarschuwd'}Nog niet betaald, betalingsgegevens: €{$extra[tickets].totaalprijs|number_format:2:",":" "} naar rekeningnummer 43.54.95.070 t.n.v. Stichting Chibicon te Utrecht onder vermelding van '{$extra[tickets].ticketnumber}'.{/if}

{/section}

Heb je nog vragen? Reageer dan gerust op dit bericht.

Met vriendelijke groet,
Stichting Chibicon
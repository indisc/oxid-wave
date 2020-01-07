<select name="[{$name}]"
        [{if $class}]class="[{$class}]"[{/if}]
        [{if $id}]id="[{$id}]"[{/if}]
        [{if $required}]required="required"[{/if}]>
    <option value="" [{if empty($value)}]SELECTED[{/if}]>[{oxmultilang ident="DD_CONTACT_SELECT_SALUTATION"}]</option>
    <option value="Lieferung" [{if $value|lower  == "mrs" or $value2|lower == "mrs"}]SELECTED[{/if}]>[{oxmultilang ident="MRS"}]</option>
    <option value="Rechnung"  [{if $value|lower  == "mr"  or $value2|lower == "mr"}]SELECTED[{/if}]>[{oxmultilang ident="MR" }]</option>
    <option value="Fragen zur Produktpalette">Fragen zur Produktpalette</option>
    <option value="Futterproben">Futterproben</option>
    <option value="Haendler - Interesse am Sortiment">Haendler - Interesse am Sortiment</option>
    <option value="Zuechter - Interesse am Sortiment">Zuechter - Interesse am Sortiment</option>
    <option value="Presse / Werbung / Sponsoring">Presse / Werbung / Sponsoring</option>
    <option value="Anregungen">Anregungen</option>
    <option value="Sonstiges">Sonstiges</option>
</select>
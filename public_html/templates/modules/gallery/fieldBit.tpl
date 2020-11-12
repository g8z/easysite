{if $field.visible}
<tr><td>{$field.title}:{if $field.required}&nbsp;<font color="red">*</font>{/if}&nbsp;</td><td>{$html}</td></tr>
{/if}
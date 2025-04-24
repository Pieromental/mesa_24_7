import { DynamicInput } from 'src/types/components/props';

export function createBaseComensalStructure(
  rules: any,
  isRequired = false
): DynamicInput[] {
  const requiredRule = isRequired ? [rules.required] : [];
  return [
    {
      key: 'id',
      label: '',
      type: 'hidden',
      value: null,
    },
    {
      key: 'nombre',
      label: 'Nombre',
      type: 'text',
      value: null,
      rules: [...requiredRule],
    },
    {
      key: 'correo',
      label: 'Correo',
      type: 'text',
      value: null,
      rules: [...requiredRule, rules.email],
    },
    {
      key: 'telefono',
      label: 'Teléfono',
      type: 'text',
      value: null,
      rules: [...requiredRule, rules.entero],
    },
    {
      key: 'direccion',
      label: 'Dirección',
      type: 'text',
      value: null,
      rules: [...requiredRule],
    },
  ];
}

import { DynamicInput } from 'src/types/components/props';

export function createBaseMesaStructure(
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
      key: 'numero_mesa',
      label: 'Número de Mesa',
      type: 'text',
      rules: [...requiredRule, rules.alfanumerico],
      value: null,
    },
    {
      key: 'capacidad',
      label: 'Capacidad',
      type: 'text',
      rules: [...requiredRule, rules.entero],
      value: null,
    },
    { key: 'ubicacion', label: 'Ubicación', type: 'text', value: null },
  ];
}

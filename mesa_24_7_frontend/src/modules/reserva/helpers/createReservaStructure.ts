import { DynamicInput } from 'src/types/components/props';

export function createBaseReservaStructure(
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
      key: 'fecha',
      label: 'Fecha',
      type: 'date',
      value: null,
      rules: [...requiredRule],
    },
    { key: 'hora', label: 'Hora', type: 'time', value: null },
    {
      key: 'numero_de_personas',
      label: 'Cantidad Personas',
      type: 'text',
      rules: [...requiredRule, rules.entero],
      value: null,
    },
  ];
}

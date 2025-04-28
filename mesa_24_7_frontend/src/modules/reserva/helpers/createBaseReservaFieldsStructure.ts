import { DynamicInput } from 'src/types/components/props';
import { createBaseReservaStructure } from './createReservaStructure';
export function createBaseReservaFieldsStructure(
  rules: any,
  isRequired = false
): DynamicInput[] {
  const requiredRule = isRequired ? [rules.required] : [];
  return [
    {
      key: 'comensal_id',
      label: 'Comensal',
      type: 'select',
      value: null,
      options: [],
      rules: [...requiredRule],
    },
    {
      key: 'mesa_id',
      label: 'Mesa',
      type: 'select',
      value: null,
      options: [],
      rules: [...requiredRule],
    },
    ...createBaseReservaStructure(rules, isRequired),
  ];
}

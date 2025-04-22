export interface ListHeaderProps {
  title: string;
  subtitle?: string;
  optionsHeader?: HeaderAction[];
}

export interface HeaderAction {
  text: string;
  color: string;
  icon: string;
  method: () => void;
}

type FilterType = 'text' | 'date' | 'time' | 'select';

export interface DynamicFilter {
  key: string;
  label: string;
  type: FilterType;
  options?: string[];
  rules?: ((val: any) => true | string)[]
}

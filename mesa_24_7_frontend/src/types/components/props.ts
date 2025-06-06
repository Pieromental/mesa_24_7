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

type FilterType = 'text' | 'date' | 'time' | 'select' | 'hidden';

export interface DynamicInput {
  key: string;
  label: string;
  type: FilterType;
  options?: any[];
  rules?: ((val: any) => true | string)[];
  value: any;
}

export interface CardItemDetail {
  icon: string;
  label: string;
  value: string;
}

export interface GenericCardItem {
  id?: string | number;
  avatarVisible: boolean;
  avatarUrl?: string;
  title: string;
  subtitle: string;
  details: CardItemDetail[];
}

export interface Comensal {
  id: string;
  nombre: string;
  correo: string;
  telefono: string;
  direccion: string | null;
  created_at: string;
  updated_at: string;
  deleted_at: string | null;
}

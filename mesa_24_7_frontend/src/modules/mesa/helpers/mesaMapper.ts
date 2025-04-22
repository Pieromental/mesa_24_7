import { GenericCardItem } from 'src/types/components/props';

export function mapMesaToCardItem(mesa: any): GenericCardItem {
  const avatarUrl = '';

  return {
    id: mesa.id,
    avatarVisible: false,
    avatarUrl,
    title: `Mesa ${mesa.numero_mesa}`,
    subtitle: `Capacidad: ${mesa.capacidad} personas`,
    details: [
      {
        icon: 'event_seat',
        label: 'Ubicación',
        value: mesa.ubicacion,
      },
      {
        icon: 'groups',
        label: 'Capacidad',
        value: `${mesa.capacidad} personas`,
      },
    ],
  };
}

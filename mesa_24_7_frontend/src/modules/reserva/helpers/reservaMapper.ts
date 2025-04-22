import { GenericCardItem } from 'src/types/components/props';

export function mapReservaToCardItem(reserva: any): GenericCardItem {
  const avatarUrl = '';

  return {
    id: reserva.id,
    avatarVisible: false,
    avatarUrl,
    title: `Reserva para ${reserva.numero_de_personas} personas`,
    subtitle: `Mesa: ${reserva.mesa_id ?? 'No especificada'}`,
    details: [
      {
        icon: 'event',
        label: 'Fecha',
        value: reserva.fecha,
      },
      {
        icon: 'access_time',
        label: 'Hora',
        value: reserva.hora,
      },
      {
        icon: 'person',
        label: 'Comensal ID',
        value: reserva.comensal_id,
      },
    ],
  };
}

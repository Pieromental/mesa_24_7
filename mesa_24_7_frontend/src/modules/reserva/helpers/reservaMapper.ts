import { GenericCardItem } from 'src/types/components/props';

export function mapReservaToCardItem(reserva: any): GenericCardItem {
  return {
    id: reserva.id,
    avatarVisible: false,
    title: `Reserva para ${reserva.numero_de_personas} personas`,

    subtitle: `Mesa: ${reserva.mesa.numero_mesa ?? 'No especificada'}`,
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
        label: 'Comensal',
        value: reserva.comensal.nombre,
      },
    ],
  };
}

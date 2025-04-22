import { Comensal, GenericCardItem } from 'src/types/components/props';

export const mapComensalToCardItem = (comensal: Comensal): GenericCardItem => {
  return {
    id: comensal.id,
    avatarVisible: true,
    avatarUrl: getRandomAvatarUrl(),
    title: comensal.nombre,
    subtitle: 'Comensal',
    details: [
      {
        icon: 'place',
        label: 'Dirección',
        value: comensal.direccion || 'No especificada',
      },
      {
        icon: 'call',
        label: 'Teléfono',
        value: comensal.telefono,
      },
      {
        icon: 'mail',
        label: 'Correo',
        value: comensal.correo,
      },
    ],
  };
};

function getRandomAvatarUrl(): string {
  const gender = Math.random() < 0.5 ? 'male' : 'female';
  const id = Math.floor(Math.random() * 50);
  return `https://xsgames.co/randomusers/assets/avatars/${gender}/${id}.jpg`;
}

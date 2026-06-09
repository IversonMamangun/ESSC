export type User = {
  id: number;
  name: string;
  user_type: UserType;
  email: string;
  phone: string;
  avatar?: string;
  email_verified_at: string | null;
  created_at: string;
  updated_at: string;
  [key: string]: unknown;
};

export type UserType = {
  id: number;
  name: string;
  slug: string;
};

export type Auth = {
  user: User;
};

export type TwoFactorConfigContent = {
  title: string;
  description: string;
  buttonText: string;
};

export type UserAddress = {
  id: number;
  label: string;
  recipient_name: string;
  recipient_number: string;
  region: string;
  province?: string;
  city: string;
  barangay: string;
  street: string;
  unit_bldg_house: string;
  postal_code: string;
  landmark?: string;
  is_default: boolean;
};

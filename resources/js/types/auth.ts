export type User = {
  id: number;
  name: string;
  user_type: UserType;
  email: string;
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

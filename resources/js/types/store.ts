export interface Store {
  id: number;
  slug: string;
  name: string;
  is_active: boolean;
  is_official: boolean;
  logo: string | null;
}

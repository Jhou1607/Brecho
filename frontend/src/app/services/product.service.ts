import { Injectable } from '@angular/core';
import { HttpClient, HttpParams } from '@angular/common/http';
import { Observable } from 'rxjs';
import { map } from 'rxjs/operators';

export interface ProductImage {
  id_imagem?: number;
  url: string;
  is_principal?: boolean;
}

export interface Product {
  id: number;
  nome_produto: string;
  preco: number;
  marca?: string;
  modelo?: string;
  estado_conservacao?: string;
  estacao?: string;
  categoria?: string;
  cor?: string;
  numeracao?: string;
  images: ProductImage[];
}

export interface PaginatedResponse<T> {
  data: T[];
  current_page: number;
  last_page: number;
  per_page: number;
  total: number;
  links: { url: string | null; label: string; active: boolean }[];
}

@Injectable({
  providedIn: 'root'
})
export class ProdutoService {
  private apiUrl = 'http://localhost:8000/api/produtos';

  constructor(private http: HttpClient) {}

  getProdutos(searchTerm?: string, page: number = 1): Observable<PaginatedResponse<Product>> {
    let params = new HttpParams();
    if (searchTerm) {
      params = params.set('term', searchTerm);
    }
    params = params.set('page', page.toString());
    return this.http.get<{ success: boolean; data: PaginatedResponse<Product> }>(this.apiUrl, { params }).pipe(
      map(response => response.data) // Extract the paginated data
    );
  }

  getProdutoById(id: number | string): Observable<any> {
    return this.http.get<{ success: boolean; data: Product }>(`${this.apiUrl}/${id}`).pipe(
      map(response => response.data)
    );
  }
}

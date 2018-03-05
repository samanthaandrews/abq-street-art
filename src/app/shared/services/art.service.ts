import {HttpClient} from "@angular/common/http";
import {Injectable} from "@angular/core";
import {HttpClient, HttpParams} from "@angular/common/http";
import {Observable} from "rxjs/Observable";
import {Art} from "../classes/art";
import {Profile} from "../classes/profile";

@Injectable()
export class ArtService {

	constructor(protected http: HttpClient) {
	}

	//define the API endpoint
	private artUrl = "api/art/";

// call to the Art API and get an Art object by its id
	getArtByArtId(id: number): Observable<Art> {
		return (this.http.get<Art>(this.artUrl + id));
	}

// call to the Art API and get an Art object by its distance
	//TODO I'm not sure why I'm getting a red underline with distance?
	getArtByDistance(distance: number): Observable<Art[]> {
		return (this.http.get<Art[]>(this.artUrl, {params: new HttpParams().set("distance", distance)}));
	}

// call to the Art API and get an Art object by its type
	getArtByArtType(artType: string): Observable<Art[]> {
		return (this.http.get<Art[]>(this.artUrl, {params: new HttpParams().set("artType", artType)}));
	}

// call to the Art API and get an Art object by its type
	getAllArts(): Observable<Art[]> {
		return (this.http.get<Art[]>(this.artUrl));
	}

}
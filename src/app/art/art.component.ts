import {Component, OnInit} from "@angular/core";
import {Art} from "../shared/classes/art";
import {ArtService} from "../shared/services/art.service";
import {Status} from "../shared/classes/status";
import {ActivatedRoute, Params} from "@angular/router";
import {Comment} from "../shared/classes/comment";

@Component({
    template: require("./art.component.html"),
})

export class ArtComponent implements OnInit {

    art: Art = new Art(null, null, null, null, null, null, null, null, null, null);

    arts: Art[] = [];
    status: Status = null;

    constructor(
       private artService: ArtService,
       private route: ActivatedRoute,
    ) {}

    ngOnInit() : void {
    	this.getArt()
    }

    getArt() : void {
		 let artId : string  = this.route.snapshot.params["artId"];

		 this.artService.getArtByArtId(artId)
			 .subscribe(art => {
				 this.art = art;
			 });
    }
}

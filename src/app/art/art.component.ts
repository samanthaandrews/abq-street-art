import {Component} from "@angular/core";
import {ArtService} from "../shared/services/art.service";

@Component({
    template: require("./art.component.html"),
    selector: "artCard"
})

export class ArtComponent implements OnInit {

    arts: Art[] = [];
    status: Status = null;

    constructor(
       private artService: ArtService,
    ) {}

    ngOnInit() : void {
        this.listArts();
        this.createArtCard = this.
    }
}